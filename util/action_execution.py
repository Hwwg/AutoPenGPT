import tempfile
import subprocess
class ActionExecution:
    def __init__(self,working_dir):
        self.working_dir = working_dir

    def script_running_process(self,code):
        with tempfile.NamedTemporaryFile(suffix=".py", delete=True, mode='w') as temp_file:
            temp_file.write(code)
            temp_file.flush()
            temp_file_path = temp_file.name
            # 使用subprocess来执行该代码文件
            try:
                result = subprocess.run(
                    ["python", temp_file_path],
                    capture_output=True,
                    text=True,
                    cwd=self.working_dir
                )
                test_result = result.stdout if result.returncode == 0 else result.stderr
                return test_result
            except Exception as e:
                return f"Error executing file: {str(e)}"
