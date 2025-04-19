from prompt.prompt_search import PromptSearch
from collections import defaultdict
import string

class SyntheticPrompt:
    def __init__(self):
        self.base_prompt = PromptSearch()

    def extract_variables(self,text):
        formatter = string.Formatter()
        return [field_name for _, field_name, _, _ in formatter.parse(text) if field_name]

    def synthesis_prompt(self,task_type,prompt_item_dic):
        """
        task_type指的是任务类型
        prompt_item_dic是一个dict类型的数据，其中的键名对应task_type中的挖空数据
        :param task_type:
        :param prompt_item_dic:
        :return:
        [{"role": "system", "content": system_prompt}]
        """
        synthesis_prompt_result = []
        system_flag = 0
        tmp_prompt_template = self.base_prompt.return_prompt_list(task_type)

        # 创建 defaultdict：不存在的 key 会默认填 ""
        safe_dic = defaultdict(str, prompt_item_dic)

        for tmp_prompt_template_item in tmp_prompt_template:
            filled_prompt = tmp_prompt_template_item.format_map(safe_dic)

            role = "system" if system_flag == 0 else "user"
            synthesis_prompt_result.append({
                "role": role,
                "content": filled_prompt
            })
            system_flag = 1

        return synthesis_prompt_result


