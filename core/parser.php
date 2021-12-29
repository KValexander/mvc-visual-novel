<?php
// Parser
class Parser {
	private $url = "";
	private $html = "";
	private $wrap = array();
	private $content = array();
	private $n = NULL;

	// Getting site content
	public function url($url) {
		$this->url = $url;
		$this->html = file_get_contents($this->url);
		return $this;
	}

	// Getting the content of a specific wrapper
	public function wrap($tag, $attr=NULL, $value=NULL, $n=NULL) {
		$this->n = $n;
		$attr = ($attr != NULL) ? $attr = " $attr=" : "";
		$value = ($value != NULL) ? $value = "(\"|')$value(\"|')" : "";
		preg_match_all("#<$tag$attr$value>(.*?)</$tag>#su", $this->html, $result, PREG_PATTERN_ORDER);
		if ($n === NULL) $this->wrap = $result[0];
		else $this->wrap = $result[0][$n];
		return $this;
	}

	// Getting links from a wrapper
	public function link($attr="href") {
		if($this->n === NULL) foreach($this->wrap as $key => $val) $this->link_processing($attr, $key, $val);
		else $this->link_processing($attr, $this->n, $this->wrap);
		return $this;
	}

	// Getting content from a wrapper
	public function content($tag, $attr=NULL, $value=NULL) {
		$attr = ($attr != NULL) ? $attr = " $attr=" : "";
		$value = ($value != NULL) ? $value = "(\"|')$value(\"|')" : "";
		if($this->n === NULL) foreach($this->wrap as $key => $val) $this->content_processing($tag, $attr, $value, $key, $val);
		else $this->content_processing($tag, $attr, $value, $this->n, $this->wrap);
		return $this;
	}

	// Link processing
	private function link_processing($attr, $key, $val) {
		preg_match_all("#$attr=(\"|')(.*?)(\"|')#su", $val, $result, PREG_PATTERN_ORDER);
		foreach(array_values(array_unique($result[0])) as $k => $v)
			$this->content[$key][$k][] = preg_replace("/(\"|\')/", "", preg_split("#$attr=#", $v)[1]);
	}

	// Content processing
	private function content_processing($tag, $attr, $value, $key, $val) {
		preg_match_all("#<$tag$attr$value>(.*?)</$tag>#su", $val, $result, PREG_PATTERN_ORDER);
		foreach($result[0] as $k => $v) {
			preg_match_all("#<(.*?)>(.*?)</(.*?)>#su", $v, $tags, PREG_PATTERN_ORDER);
			foreach($tags[0] as $c)
				if(trim(strip_tags($c)) != "")
					$this->content[$key][$k][] = trim(strip_tags($c));
		}
	}

	// Get html
	public function get_html() {
		return $this->html;
	}

	// Get wrap
	public function get_wrap() {
		return $this->wrap;
	}

	// Get content
	public function get_content() {
		$content = ($this->n === NULL) ? $this->content : $this->content[$this->n];
		$this->content = array();

		return $content;
	}
}
?>