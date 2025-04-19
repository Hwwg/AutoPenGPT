from prompt.prompt_content import *


class PromptSearch:
    def __init__(self):
        pass

    def return_prompt_list(self, task_type):
        if task_type == "data_restatement":
            return [Data_Restatement_system,Data_Restatement_user]
        elif task_type == "vulnerability_type_prediction":
            return [Vulnerability_type_prediction_system,Vulnerability_type_prediction_user]
        elif task_type == "pak_generation":
            return [Pak_gen_system,Pak_gen_user]
        elif task_type == "pak_generation_update":
            return [Pak_gen_update_system,Pak_gen_update_user]
        elif task_type == "strategy_generation":
            return [Strategy_formulation_system,Strategy_formulation_user]
        # elif task_type == "strategy_result_check":
        #     return [Strategy_check_system, Strategy_check_user]
        elif task_type == "goal_reset":
            return [goal_reset_system,goal_reset_user]
        elif task_type == "strategy_scripting":
            return [Strategy_scripting_system, Strategy_scripting_user]
        elif task_type == "script_check":
            return [Script_check_system,Script_check_user]
        elif task_type == "result_comparison":
            return [Result_judgement_system,Result_judgement_user]
        elif task_type =="historical_data_record":
            return [Historical_data_system, Historical_data_user]
        elif task_type == "html_code_formatting":
            return [html_code_formatting_system, html_code_formatting_user]
