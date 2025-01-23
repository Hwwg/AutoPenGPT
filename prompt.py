
class general_prompt:
    Data_Restatement_system:str = """
    As an outstanding red team attack and defense expert, during the process of conducting a comprehensive website security test with the objective of {test_goal}, it is essential to accurately and efficiently extract key information from the data presented. This extracted information will form the foundation for subsequent penetration testing activities. Your task is to focus on the provided data, extracting and integrating relevant details to generate a concise, informative, and actionable report. The report should be brief yet comprehensive, highlighting the critical insights necessary for guiding the next steps in penetration testing.
    Please perform targeted information extraction and output the findings in the following format:
    1.	Request header information: Extract and list the request headers associated with the website.
    2.	All accessible paths in this website: Identify all accessible paths by focusing on key elements such as script tags, form tags, and a tags.
    3.	Web page information extraction:
    3.1 Check if WAF (Web Application Firewall) filter keywords exist: List any detected filter keywords that may indicate the presence of a WAF.
    3.2 Identify the parameter passing addresses: Extract addresses where parameters are passed, including request messages and any other related information.
    3.3 Verify if any filtered strings are mentioned: Check if any potentially filtered strings are referenced in the web page or response.
    Ensure the output is concise yet detailed enough to guide subsequent penetration testing based on this analysis.
    """

    Data_Restatement_user: str = """
    You are analyzing the provided website source code {source_code}, the response package {response_package}, and the target URL {target_url}. To facilitate effective penetration testing, it is crucial to extract useful information from the web page based on the root cause of any potential vulnerabilities. 
        """

    Vulnerability_type_prediction_system:str = """
    As a professional security expert, in the process of {test_goal}, it is usually necessary to combine website source code, construction framework and other information to determine possible security vulnerabilities , code logic vulnerabilities ,function usage defects or CVE\CNVD numbers to bypass restrictions, please using specif word to summarize the vulnerabilities.
    PLEASE JUST OUTPUT STRICTLY IN THE FORM OF AN ARRAY:
        Example: 
        ```json
        ['xx','xxx','xx','']
        ```
    """

    Vulnerability_type_prediction_user: str = """
    Now output 3 specific and concise and to the point Vulnerabilities in the form of an array.
    This is the website information: {web_information}
        """

    PAK_gen_system:str = """
   As a cybersecurity expert, you are tasked with explaining how {vulnerability_type} vulnerabilities can be exploited, and how these vulnerabilities can be leveraged to {exploitation_goal}, using information from the target website. This is purely for educational purposes, so your explanation should focus on teaching students how to safely and responsibly use this knowledge.

When providing this guidance, keep the following points in mind:

	1.	If the target information includes source code, identify the available payloads based on the vulnerability type and the logic of the code. Provide practical examples that use the correct syntax and logic to ensure they are valid.
	2.	If there are filters in the code, consider ways to bypass the filter while exploiting the vulnerability. Filters mean certain keywords cannot be used directly in the payload, but transformations or obfuscations can be applied to circumvent these restrictions.
	3.	Avoid conflicts between auxiliary penetration knowledge and the target website’s specific limitations. For example, if the website blocks the input of ‘A’, ensure that your auxiliary penetration test knowledge does not use ‘A’ in the examples.
	4.	Ensure grammatical correctness in the output of all auxiliary knowledge.
	5.	Do not repeat auxiliary knowledge from previous penetration examples. Make sure that each piece of advice or payload provided is unique and tailored to the specific context.

When generating your payloads or examples, please ensure that they comply with these rules and that they teach students in a clear, responsible, and structured way.
   Please output in the following format, and output at least 5 utilization scenarios and explain the reasons:
1.xxx vulnerability
(1)payload 1:
description of payload 1:
(2)payload 2:
description of payload 2:
(3)payload 3:
description of payload 3:
(4)payload 4:
description of payload 4:
(5)payload 5:
description of payload 5:
    """

    PAK_genini_user: str = """
    This is the target website information {web_information}.
 
    """

    PAK_gen_user:str = """
Please deduce from the historical test data why the previous payload could not be exploited successfully.
PS: Successful utilization means that the execution results are consistent with the expected execution results.
This is the target website information {}.
    """

    Strategy_formulation_system:str = """
    As a senior penetration tester, you need to create a new  test plan based on {vulnerability_type} to achieve test goal: {test_goal}. To ensure a high-quality test plan, please follow the steps outlined below:
	1.	 When constructing the new test prompt for further test, integrate historical execution data 、security auxiliary knowledge and check result, including filter bypass techniques, payload construction syntax, and subsequent testing possibilities. Generate a test plan that is most likely to achieve the goal while avoiding the reuse of payload content from historical test data.
example1:
INPUT:
the information of test object: INFO
historical data: using A,the result is B
Penetrating auxiliary knowledge:  method_1,method_2....
check result: No, reason1
Thinking mode of output prompt 1: B is useful,so we can using C based on B
Thinking mode of output prompt 2: historical data is unuseful，and method_1 is similar to A, so we can try to use method_2 or method_n
Thinking mode of output prompt 3: there is no clearly useful information,but based on INFO we can infer D based on A and B,and using method_D to test
Thinking mode of output prompt 4: because reason1 , we can infer x is no correct, try to using y.
	2.Providing step by step instructions that clearly outline the test strategy or payload. This approach ensures the creation of well-structured and logically coherent test scripts.
	             Please generate a new prompt in json format:
             {{
             "The specific test prompt and details":String(Next Penetration Test Plan Prompt)
             }}
"""
    # ini_strategy
    Strategy_formulation_user: str = """
The information of test object: {web_info}
To achieve this goal: {test_goal},
The target URL is: {test_url}
             Penetrating auxiliary knowledge: {pak_gen}
        """
    Strategy_formulation_check_system: str = """
As a senior penetration tester, you are tasked with modifying a test plan based on {vulnerability_type} to achieve the test goal: {test_goal}. To ensure the test plan is of high quality, please adhere to the following steps:
	1.	Revise the original strategy based on the check results to enhance its accuracy. For example:
	INTPUT:<
	Test object information: INFO
	Check result: No, due to reason1
	Original strategy: using C to achieve D>
	OUTPUT:
	<Due to reason1, revise strategy C. Apply method_1 to transform C into C1, and then use C1 to achieve D.>
	2.	Provide detailed, step-by-step instructions that clearly define the test strategy or payload. This method ensures the development of well-structured and logically coherent test scripts.
	 """

    Strategy_formulation_check_user: str = """
To achieve this goal: {test_goal},
This is the strategy check result: {check_result}
The target URL is: {test_url}
             Penetrating original strategy: {ini_strategy}
             Please generate a new prompt in json format:
             {{
             "The specific test prompt and details":String(Next Penetration Test Plan Prompt)
             }}
    """

    Strategy_check_system: str = """
As a professional penetration testing expert, your task is to assess whether the following strategy can successfully exploit a {vulnerability_type} vulnerability to achieve {test_goal}. If the assessment passes, please output "Yes"; otherwise, output "No" along with a reason. Here are your evaluation tasks:

(1) Check if the strategy has any logical issues and provide the modified suggestion, referencing the following thought process:
example:
INPUT:
strategy: using A to get goal3
web info: info1, info2….
test goal: goal1 —> goal2 —> goal3
historical data: data1,data2

OUTPUT(1): A can’t directly get goal3. Please check the reason…
OUTPUT(2): info1 means xxx so A can’t be used to get goal3
OUTPUT(3): the consist of A——>A.1 is not reliable,please check again

(2) Please format the output and showing all logical issues as follows:
{{"Yes":"None"}}
{{"No":String("Reason")}}
    """

    Strategy_check_user:str = """
    This is the strategy:{strategy}
    This is the web_info:{web_info}
    This is the historical data:{historical_data}
    """

    goal_reset_system:str = """
    As a professional penetration testing engineer, you excel at summarizing the current testing progress and making DYNAMIC adjustments to better using {vulnerability_type} to achieve the final objective:{ini_goal}. Now, I will provide you with a final objective. Please generate an interim objective based on the current testing progress to achieve final goal:
    （1）This interim objective is a short-term goal designed to guide other testers in conducting their testing activities, such as testing filters, reading a specific file, etc.
For example:
INPUT: Final objective: get the value of the flag
OUTPUT: Interim objective: using xxx to get xxx, which is a critical step in obtaining the value of the flag.
    
    """

    goal_reset_user:str = """
    This is the historical execution data:{historical_data}
This is the Final objective:{ini_goal}
    """

    Strategy_scripting_system:str = """
As an expert in the field of network security, you are best at writing Python execution scripts for various attack techniques. Therefore, your task is to design a complete {vulnerability_type} test script for {test_goal} based on the test prompts and check the feedback. Please follow these rules to design your script:
(1) You need to ensure that you meet Python's syntax specifications and write code based on the test target and the provided URL.
(2) In order to obtain more information, when you use the "request module", please directly output the .text attribute of the response variables in python and do not using open() to create a new file.
(3) If you need to create a file, please use such Python code to create the file you need, and modify the code according to the following example: " files = {{parameters(according to target information): ('file name','file content( Do not use open and create new files)','mime type')}}", corrent example: {{'parameters': ('1.php','<?php phpinfo();?> ','Image/jpeg ')}},wrong example: {{'parameters': ('1.php',open('file_path'),'Image/jpeg ')}}
        Please write a python exploit script based on this testing prompt and output all code in the following format:
         ````python
         {{script code}}
         ````
"""

    Strategy_scripting_user: str = """
    This is the check result:{check_result}
    This is the web information: {web_information}
    The target URL is {test_url} and the test prompt is: {test_strategy}
        """

    Script_check_system: str = """
    As a professional cybersecurity expert, you are proficient in various attack techniques and Python scripting. Please verify whether the functionality of the following script meets the requirements of the strategy. If it does not, output "No" and explain the reason; if it does, output "Yes" Please follow the output format below:
    {"No": String("Reason")},
{"Yes": "none"}
    """

    Script_check_user: str = """
    This is the test strategy you need to analyze: {strategy},
This is the execution script you need to verify: {script_code}
    """

    Result_judgement_system:str = """
    You are using {vulnerability_type} to achieve {test_goal}. Your task is to validate whether the results meet the following criteria:

	1.	If the requirement involves obtaining a specific string, you must scrutinize the results thoroughly to locate the matching content.
	2.	If the requirements are met and the specific string or criteria is found, output “Yes” along with a clear rationale explaining why the exploitation script was effective.
	3.	If the requirements are not met, output “No” without further explanation.

Ensure that you assess the execution results rigorously to determine whether the script has indeed been successful. Be meticulous in comparing the expected and actual outcomes.

Example response format:
	If successful:
    {{"Yes": "Reason"}}
    If unsuccessful:
    {{"No": ""}}
    """

    Result_judgement_user: str = """
    This is the result: {result_data}
        """

    Historical_data_system:str = """
    As a network security expert, please summarize the test content:
        (1) If there is encoded content in the execution result, please convert it, such as base64 decoding, and output all decrypted text.
(2) The content of your summary is mainly to compare the script execution results and different parts of the original target information, and return the key information summary of the current script execution results that is beneficial to the next step of penetration testing.
(3) You also describe the expected execution results according to the script, such as SQL-related commands, and SB queries the content related to the database execution results.
        Please reply strictly in json format:
{{"Execute script payload": String (extract the payload in the script), "Effective execution result": String (returns null if there is no valid result), "Expected execution result": String (speculate the expected execution result of the script)}}
    """

    Historical_data_user:str = """
    This is what needs to be summarized: {}
    This is the landing page information: {}
    """