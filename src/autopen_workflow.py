import json
import threading

from gptreply.gpt_con import GPTReply
from src.synthesis_prompt import SyntheticPrompt
from util.web_crawler import WebCrawler
from util.action_execution import ActionExecution
import re
import os
import colorlog
import logging
from datetime import datetime
import uuid

handler = colorlog.StreamHandler()
handler.setFormatter(colorlog.ColoredFormatter(
    '%(log_color)s%(asctime)s | %(levelname)-8s | %(message)s',
    datefmt='%Y-%m-%d %H:%M:%S',
    log_colors={
        'DEBUG':    'cyan',
        'INFO':     'green',
        'WARNING':  'yellow',
        'ERROR':    'red',
        'CRITICAL': 'bold_red',
    }
))
logging.getLogger("httpx").setLevel(logging.WARNING)
logging.getLogger("openai").setLevel(logging.WARNING)
logging.getLogger("urllib3").setLevel(logging.WARNING)
logger = colorlog.getLogger()
logger.addHandler(handler)
logger.setLevel(logging.INFO)

class AutoPenWorkFlow:
    def __init__(self,model,
                client,
                test_object,
                test_goal,
                test_vul_type_numbers,
                file_cache_path,
                results_pattern_type,
                results_rule_pattern,
                log_file_path,
                stop_event,
                stop_flag,
                referenct_document=""
                ):
        """
        self.test_object 指的是测试对象
        self.test_goal  指的是测试目标
        self.test_vul_type_numbers 指的是需要测试的测试用例数量
        :param model:
        :param client:
        :param test_object:
        :param test_goal:
        :return:
        """
        self.gpt_reply = GPTReply(model,client)
        self.syn_prompt = SyntheticPrompt()
        self.webcrawler = WebCrawler(self.gpt_reply,self.syn_prompt)
        self.test_object = test_object
        self.test_goal = test_goal
        self.test_vul_type_numbers = test_vul_type_numbers
        self.stop_event = stop_event
        self.initial_test_info_dict = {
            "test_object":self.test_object,
            "test_goal" :self.test_goal,
            "test_object_initial_page_info":self.webcrawler.web_crawler_tool(self.test_object),
            "endpoint_types":self.webcrawler.endpoint_type_extraction(self.test_object),
            "test_vul_type_numbers" : self.test_vul_type_numbers,
            "historical_data_result" : [],
            "tmp_goal_list" : [],
            "reference_document":referenct_document,
            "token_usage":""
        }
        # self.initial_test_info_dict["historical_data_result"]
        self.file_cache_path = file_cache_path
        self.action_execution = ActionExecution(self.file_cache_path)
        self.log_file_path = log_file_path
        self.results_pattern_type = results_pattern_type
        self.stop_flag = stop_flag
        if self.results_pattern_type == "rule":
            self.results_rule_pattern = results_rule_pattern
        log_dir = os.path.dirname(self.log_file_path)
        os.makedirs(log_dir, exist_ok=True)

    def code_formatting(self, data):
        pattern = r'```python(.*?)```'
        matches = re.findall(pattern, data, re.DOTALL)
        return matches[0].strip() if matches else ""

    def list_formatting(self,data):
        pattern = r'```json(.*?)```'
        matches = re.findall(pattern, data, re.DOTALL)
        return matches[0].strip() if matches else ""

    def adaptive_knowledge_base_generation(self,iteration_phase):
        """
        首先是生成，其次是迭代更新
        :param test_object_information:
        :return:
        """
        if iteration_phase == "initial":
            self.initial_test_info_dict["adaptive_knowledge_results"] = self.gpt_reply.getreply(
                self.syn_prompt.synthesis_prompt("pak_generation",self.initial_test_info_dict)
            )
        else:
            # vul_type_prediction_result = self.gpt_reply.getreply(self.syn_prompt.synthesis_prompt("vulnerability_type_prediction",initial_test_info_dict))
            self.initial_test_info_dict["adaptive_knowledge_results"] = self.gpt_reply.getreply(
                self.syn_prompt.synthesis_prompt("pak_generation_update",self.initial_test_info_dict)
            )

        return True

    def strategies_script_generation(self):
        self.initial_test_info_dict["strategy_scripts"] = self.gpt_reply.getreply(
            self.syn_prompt.synthesis_prompt("strategy_scripting", self.initial_test_info_dict)
        )
        return self.initial_test_info_dict["strategy_scripts"]



    def dynamic_strategies_generation_and_execution(self,iteration_phase):
        """

        :return:
        """
        if iteration_phase == "initial":
            self.initial_test_info_dict["tmp_goal"] = self.test_goal

        else:
            self.initial_test_info_dict["tmp_goal"] = self.gpt_reply.getreply(
                self.syn_prompt.synthesis_prompt("goal_reset", self.initial_test_info_dict)
            )
            self.initial_test_info_dict["tmp_goal_list"].append(self.initial_test_info_dict["tmp_goal"])



        self.initial_test_info_dict["strategy_result"] = self.gpt_reply.getreply(
            self.syn_prompt.synthesis_prompt("strategy_generation", self.initial_test_info_dict)
        )


        while True:
            self.initial_test_info_dict["code_formatting_script"] = self.code_formatting(
                self.strategies_script_generation()
            )
            break


        self.initial_test_info_dict["execution_result"] = self.action_execution.script_running_process(
            self.initial_test_info_dict["code_formatting_script"]
        )

        pass


    def dynamic_strategies_generation_and_execution_variant2(self,iteration_phase):
        """

        :return:
        """
        if iteration_phase == "initial":
            self.initial_test_info_dict["tmp_goal"] = self.test_goal
        #
        # else:
        #     self.initial_test_info_dict["tmp_goal"] = self.gpt_reply.getreply(
        #         self.syn_prompt.synthesis_prompt("goal_reset", self.initial_test_info_dict)
        #     )
        #     self.initial_test_info_dict["tmp_goal_list"].append(self.initial_test_info_dict["tmp_goal"])



        self.initial_test_info_dict["strategy_result"] = self.gpt_reply.getreply(
            self.syn_prompt.synthesis_prompt("strategy_generation", self.initial_test_info_dict)
        )


        while True:
            self.initial_test_info_dict["code_formatting_script"] = self.code_formatting(
                self.strategies_script_generation()
            )
            break


        self.initial_test_info_dict["execution_result"] = self.action_execution.script_running_process(
            self.initial_test_info_dict["code_formatting_script"]
        )

        pass

    def result_comparison_process(self):
        if self.results_pattern_type == "rule":
            if self.results_rule_pattern in self.initial_test_info_dict["execution_result"]:
                return True

        elif self.results_pattern_type == "semantics":
            self.initial_test_info_dict["comparison_results"] = self.gpt_reply.getreply(
                self.syn_prompt.synthesis_prompt("result_comparison",self.initial_test_info_dict
                                                 )
            )
            if "YES" in self.initial_test_info_dict["comparison_results"].upper():
                return True

        return False

    def historical_data_record_process(self):
        self.initial_test_info_dict["historical_data_result"].append(
            self.gpt_reply.getreply(
                self.syn_prompt.synthesis_prompt("historical_data_record",self.initial_test_info_dict)
            )
        )

    def historical_data_record_process_variant2(self):
        self.initial_test_info_dict["historical_data_result"].append("")

    # def middle_testing_process(self):

    def write_jsonl_with_id(self,is_success):
        """
        给 self.initial_test_info_dict 添加唯一 ID，并将其写入 JSONL 文件。
        """
        if is_success:
            self.initial_test_info_dict["is_success"] = True
        unique_id = f"{datetime.now().strftime('%Y%m%d-%H%M%S-%f')}_{uuid.uuid4().hex[:8]}"
        self.initial_test_info_dict["id"] = unique_id

        with open(self.log_file_path, "a", encoding="utf-8") as f:
            f.write(json.dumps(self.initial_test_info_dict) + "\n")

    def attack_main_workflow(self):
        """
        主要的workflow
        :return:
        """
        # 自适应性测试用例库生成
        self.initial_test_info_dict["test_object_information"] = self.gpt_reply.getreply(
            self.syn_prompt.synthesis_prompt("data_restatement",self.initial_test_info_dict)
        )
        while True:
            try:
                self.initial_test_info_dict["vul_type_prediction_result_list"] = self.gpt_reply.getreply(
                    self.syn_prompt.synthesis_prompt("vulnerability_type_prediction",self.initial_test_info_dict)
                )
                # logger.info("VulnTypeList: %s",
                #             self.initial_test_info_dict.get("vul_type_prediction_result_list"),
                #             )
                self.initial_test_info_dict["vul_type_prediction_result_formatted_list"] = eval(
                    self.list_formatting(
                        self.initial_test_info_dict["vul_type_prediction_result_list"]
                    )
                )

                break
            except:
                pass
        #todo: 添加是否要多进程处理不同漏洞类型的测试过程
        # 实现动态测试过程
        for vuln_type_item in self.initial_test_info_dict["vul_type_prediction_result_formatted_list"]:
            self.initial_test_info_dict["vul_type_prediction_item"] = vuln_type_item

            self.adaptive_knowledge_base_generation("initial")
            self.dynamic_strategies_generation_and_execution("initial")

            # logger.info("VulnType: %s | Goal: %s",
            #             self.initial_test_info_dict.get("vul_type_prediction_item"),
            #             self.initial_test_info_dict.get("tmp_goal")
            #             )
            flag = 0
            while not self.stop_event.is_set() and flag < self.stop_flag:
                if self.result_comparison_process():
                    self.historical_data_record_process()
                    self.dynamic_strategies_generation_and_execution("process")
                    self.adaptive_knowledge_base_generation("process")

                    logger.info(
                        "\n\033[94m=== [Success!GET YOUR GOAL!] Vulnerability Type: %s ===\033[0m\n🎯 Goal: %s\n📜 Test Case Summary (%d items):\n%s\n",
                        self.initial_test_info_dict.get("vul_type_prediction_item"),
                        self.initial_test_info_dict.get("tmp_goal"),
                        len(self.initial_test_info_dict.get("historical_data_result", [])),
                        "\n".join(f"  [{i+1}] {item}" for i, item in enumerate(self.initial_test_info_dict.get("historical_data_result", [])))
                    )

                    self.write_jsonl_with_id(True)
                    self.stop_event.set()
                    self.initial_test_info_dict["token_usage"] = self.gpt_reply.get_total_tokens()
                    return True
                else:
                    self.historical_data_record_process()
                    self.dynamic_strategies_generation_and_execution("process")

                    logger.info(
                        "\n>>> Vulnerability Type: %s\n🎯 Goal: %s\n📜 Progress Summary (%d items):\n%s\n",
                        self.initial_test_info_dict.get("vul_type_prediction_item"),
                        self.initial_test_info_dict.get("tmp_goal"),
                        len(self.initial_test_info_dict.get("historical_data_result", [])),
                        "\n".join(f"  [{i+1}] {item}" for i, item in enumerate(self.initial_test_info_dict.get("historical_data_result", [])))
                    )
                    flag+=1
                    self.initial_test_info_dict["token_usage"] = self.gpt_reply.get_total_tokens()
                    self.write_jsonl_with_id(False)
            if flag == self.stop_flag:
                logger.info(
                    "\n\033[91m=== [Failed] Vulnerability Type: %s ===\033[0m\n🎯 Goal: %s\n📜 Test Case Summary (%d items):\n%s\n",
                    self.initial_test_info_dict.get("vul_type_prediction_item"),
                    self.initial_test_info_dict.get("tmp_goal"),
                    len(self.initial_test_info_dict.get("historical_data_result", [])),
                    "\n".join(f"  [{i + 1}] {item}" for i, item in
                              enumerate(self.initial_test_info_dict.get("historical_data_result", [])))
                )


    def main_classification(self):
        """
        当功能点仅一个或者很多个时
        :return:
        """
        for key,value in self.initial_test_info_dict["endpoint_types"].items():
            self.initial_test_info_dict["target_endpoint_types"] = value
            if self.attack_main_workflow():
                break

