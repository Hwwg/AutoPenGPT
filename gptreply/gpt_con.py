from openai import OpenAI
import logging
logging.basicConfig(level=logging.INFO, format="%(asctime)s | %(levelname)s | %(message)s")
logger = logging.getLogger(__name__)

class GPTReply:
    def __init__(self, model, client="openai"):
        self.model = model
        self.client = client
        self.total_input_tokens = 0  # 记录总输入 token 数
        self.total_output_tokens = 0  # 记录总输出 token 数
        self.total_cost = 0.0  # 记录总费用

        # 定义 GPT-4o mini 的价格（单位：美元）
        self.pricing = {
            "input": 2.50 / 1_000_000,  # 输入 token 价格
            "cached_input": 1.25 / 1_000_000,  # 缓存输入 token 价格
            "output": 10 / 1_000_000,  # 输出 token 价格
        }

    def getreply(self, messages):
        while True:
            try:
                if self.client == "openai":
                    client = OpenAI(api_key="", base_url="")
                #



                completion = client.chat.completions.create(
                    model=self.model,
                    messages=messages,
                    temperature=0.7
                )


                # 获取输入和输出的 token 数
                input_tokens = completion.usage.prompt_tokens
                output_tokens = completion.usage.completion_tokens

                # 累加输入和输出的 token 数
                self.total_input_tokens += input_tokens
                self.total_output_tokens += output_tokens

                # 计算本次请求的费用并累加
                cost = self._calculate_cost(input_tokens, output_tokens)
                self.total_cost += cost

                return completion.choices[0].message.content

            except Exception as e:
                print(str(e))
                if "maximum context length is" in str(e) or "Range of input length should" in str(e) or "Exceeded limit on max byt" in str(e):
                    logger.error(f"[Token Limit Exceeded] Model input exceeded max token context length.\nError: {e}")
                    raise RuntimeError("Token limit exceeded — prompt too long.")
                else:
                    logger.warning(f"[LLM Error] Unhandled error: {e}")
                pass

    def _calculate_cost(self, input_tokens, output_tokens):
        """根据输入和输出的 token 数计算费用"""
        input_cost = input_tokens * self.pricing["input"]
        output_cost = output_tokens * self.pricing["output"]
        return input_cost + output_cost

    def get_total_tokens(self):
        """返回总输入和输出的 token 数"""
        return f"{self.total_input_tokens},{self.total_output_tokens}"

    def get_total_cost(self):
        """返回总费用"""
        return self.total_cost