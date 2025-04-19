# 🎯 AutoPenGPT: Adaptive Vulnerability Testing and Exploitation Guided by User Intent
## 🚀 Usage

1. ✅ **Install required Python packages**  
Use the following command to install all dependencies:
```bash
python -m pip install -r requirements.txt
```
2. 🔑 Configure your OpenAI API key
Open the file gptreply/gpt_con.py and fill in your:
- penai_api_key
- base_url
3.	🧪 Run AutoPenGPT:
```commandline
python run_autopen.py --test-object http://localhost:3000 --test-goal "test vuln" --results-pattern-type rule --results-rule-pattern "test"
```

## 📦 Test Sets
- All challenge tasks are included in the ctf_testsets directory, including AutoPenBench-ws 🧩
- For CVE reproduction tasks, you can download corresponding Docker environments from Vulhub based on the CVE ID 🔧🐳
