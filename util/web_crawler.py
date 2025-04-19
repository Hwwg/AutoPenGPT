import requests
from bs4 import BeautifulSoup
import json
from src.synthesis_prompt import SyntheticPrompt
import re
class WebCrawler:
    def __init__(self,gpt_reply,syn_prompt):
        self.gpt_reply = gpt_reply
        self.syn_prompt = syn_prompt

    def web_crawler_tool(self, test_object):
        """
        我们主要focus on 的是一个页面的功能点，并且不考虑各功能点之间的联系
        :param test_object:
        :return:
        """
        results = requests.get(test_object).text
        clean_code = self.clean_html_keep_content(results)
        return clean_code

    def endpoint_type_extraction(self, test_object):
        """
        我们主要focus on 的是一个页面的功能点，并且不考虑各功能点之间的联系
        :param test_object:
        :return:
        """
        results = requests.get(test_object).text
        clean_code = self.clean_html_keep_content(results)
        formatting_dict_code = self.endpoint_classification(clean_code)
        return formatting_dict_code

    def list_formatting(self,data):
        pattern = r'```json(.*?)```'
        matches = re.findall(pattern, data, re.DOTALL)
        return matches[0].strip() if matches else ""

    def endpoint_classification(self,html_code):
        while True:
            try:
                html_code_dict = eval(self.list_formatting(self.gpt_reply.getreply(
                    self.syn_prompt.synthesis_prompt("html_code_formatting", {"html_code":html_code})
                )))
                break
            except:
                pass
        return html_code_dict


    def clean_html_keep_content(self,html):
        soup = BeautifulSoup(html, 'html.parser')

        # Remove non-semantic tags and purely decorative SVG-related elements
        for tag in soup.find_all(['style', 'path', 'circle', 'line', 'polyline', 'polygon', 'rect', 'ellipse', 'link']):
            tag.decompose()

        # Remove <svg> if it lacks semantic info like <title>, <desc>, or role attributes
        for svg in soup.find_all('svg'):
            if not (svg.find('title') or svg.find('desc') or svg.get('role')):
                svg.decompose()

        # Remove external script tags but preserve inline ones
        for script in soup.find_all('script'):
            if script.get('src'):
                script.decompose()

        # Additionally remove <img> elements without alt/title (purely decorative)
        for img in soup.find_all('img'):
            if not (img.get('alt') or img.get('title')):
                img.decompose()

        return soup.get_text()

#