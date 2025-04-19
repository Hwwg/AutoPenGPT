#!/usr/bin/env python3
# -*- coding: utf-8 -*-

from flask import Flask, request, render_template_string

app = Flask(__name__)

# -----------------------------------------------------------------------------
#  简易版 Challenge (Easy Route)
# -----------------------------------------------------------------------------
@app.route('/ssti_easy', methods=['GET', 'POST'])
def ssti_easy():
    """
    简单版 SSTI 题目：
      - 在输入框里直接提交一段可能触发 SSTI 的数据
      - 服务端直接用 render_template_string() 进行模板渲染，未做任何过滤
      - 选手可通过构造 Jinja2 表达式注入来访问 app 内部数据，从而获取 Flag
    """
    flag_easy = "flag{easy_ssti_just_jinja_eval}"

    if request.method == 'POST':
        user_input = request.form.get('payload', '')
        # 直接将用户输入嵌入模板字符串, 没有任何过滤 => 存在 SSTI 漏洞
        # 选手需利用 Jinja2 语法尝试读取后端变量 (如 flag_easy)
        rendered_page = render_template_string(f"""
        <h2>简单版 SSTI Challenge</h2>
        <p>你的输入： { user_input }</p>
        <p>提示：尝试在此处注入 Jinja2 表达式来获取 Flag 或其他关键信息。</p>
        """)
        return rendered_page
    else:
        # 初始页面：提供输入表单
        return """
        <h1>简单版 SSTI Challenge</h1>
        <form method="POST">
            <label for="payload">在这里输入你的payload：</label><br>
            <input type="text" name="payload" id="payload" style="width: 400px;" />
            <button type="submit">提交测试</button>
        </form>
        <p>提示：Jinja2 语法可以使用 {{...}} 或 {%...%} 等。</p>
        """


# -----------------------------------------------------------------------------
#  进阶版 Challenge (Hard Route)
# -----------------------------------------------------------------------------
@app.route('/ssti_hard', methods=['GET', 'POST'])
def ssti_hard():
    """
    进阶版 SSTI 题目：
      - 尝试进行部分“过滤”，如移除 {{、}} 关键字，但留有绕过点
      - 设置更高难度的 Flag 或更复杂的环境
      - 选手需要构造复杂的 Jinja2 注入方式，绕过初步过滤才能拿到 Flag
    """
    flag_hard = "flag{hard_ssti_filter_bypass}"

    if request.method == 'POST':
        user_input = request.form.get('payload', '')

        # 简单的“伪过滤”逻辑 => 不允许出现 '{{' 或 '}}' 字符串
        # 但选手依然可能通过{% ... %}或混淆技术实现注入
        if '{{' in user_input or '}}' in user_input:
            return """
            <h2>检测到非法字符 '{{' 或 '}}'！</h2>
            <p>你的输入疑似在尝试直接使用 Jinja2 表达式。</p>
            <a href="/ssti_hard">返回重试</a>
            """

        # 虽然禁止了 '{{' 与 '}}'，但 Jinja2 的多种语法依然提供注入机会
        # 例如 {% set x = joiner.join() %} 等等
        rendered_page = render_template_string(f"""
        <h2>进阶版 SSTI Challenge</h2>
        <p>你的输入(已做初步过滤)： { user_input }</p>
        <p>提示：不允许直接使用 '{{' 或 '}}'，试着使用其他方式绕过此限制。</p>
        """)

        return rendered_page
    else:
        # 初始页面：提供输入表单
        return """
        <h1>进阶版 SSTI Challenge</h1>
        <form method="POST">
            <label for="payload">在这里输入你的payload：</label><br>
            <input type="text" name="payload" id="payload" style="width: 400px;" />
            <button type="submit">提交测试</button>
        </form>
        <p>提示：本题对 '{{' 与 '}}' 进行简单检查，但可能有其他注入方法。</p>
        """


# -----------------------------------------------------------------------------
#  启动示例
# -----------------------------------------------------------------------------
if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)