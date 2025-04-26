import tiktoken

class ActionExecution:
    def __init__(self, working_dir, token_length, original_text, model):
        self.working_dir = working_dir
        self.token_length = token_length
        self.original_text = original_text
        self.model = model

    def count_tokens(self, text):
        enc = tiktoken.encoding_for_model(self.model)
        return len(enc.encode(text))

    def script_running_process(self, code):
        with tempfile.NamedTemporaryFile(suffix=".py", delete=True, mode='w') as temp_file:
            temp_file.write(code)
            temp_file.flush()
            temp_file_path = temp_file.name
            try:
                result = subprocess.run(
                    ["python", temp_file_path],
                    capture_output=True,
                    text=True,
                    cwd=self.working_dir
                )
                test_result = result.stdout if result.returncode == 0 else result.stderr

                if self.count_tokens(test_result) > (self.token_length*0.8):
                    return self.find_different_content(self.original_text, test_result)
                else:
                    return test_result
            except Exception as e:
                return f"Error executing file: {str(e)}"


    def find_different_content(self, text_original, text_new):
        matcher = SequenceMatcher(None, text_original, text_new)
        diffs = []
        for tag, i1, i2, j1, j2 in matcher.get_opcodes():
            if tag in ('insert', 'replace'):
                diffs.append(text_new[j1:j2])
        return ''.join(diffs).strip()
