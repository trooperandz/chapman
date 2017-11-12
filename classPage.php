<?php 
class Page { 
	public $type = "default"; 
	public $title = "My Web Site"; 
	public $titleExtra = ""; 
	
	public function getTop() { 
		$output = ""; $output .= $this->_getDocType(); 
		$output .= $this->_getHtmlOpen(); 
		$output .= $this->_getHead(); 
		$output .= file_get_contents("pageTop.txt"); 
		return $output; 
	} // end function getTop()
	
	protected function _getDocType($doctype = "html5") { 
		if ($doctype == "html5") { 
			$dtd = "<!DOCTYPE html>"; 
			} 
		return $dtd . "\n"; 
	}
	
	protected function _getHtmlOpen($lang = "en-us") { 
		if ($lang == "en-us") { 
			$htmlopen = "<html lang =\"en\">"; 
			} 
		return $htmlopen . "\n"; 
	}
	
	protected function _getHead() { 
		$output = "";
		
		if ($this->type == "contact") {
				$output .= file_get_contents("pageHeadContact.txt");
			} else {
			$output .= file_get_contents("pageHead.txt");
			}
			
		if ($this->titleExtra != "") { 
			$title = $this->titleExtra . "|" . $this->title; 
		} else { 
			$title = $this->title; 
		} 
		$output .= "<title>" . $title . "</title>"; 
		$output .= "</head>"; 
		return $output; 
	} // end function _getHead()
	
	public function getBottom() { 
		return file_get_contents("pageBottom.txt");
	} // end function getBottom()




} // end Page class 
?>

