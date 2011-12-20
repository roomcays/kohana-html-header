HTML Header
====================

Helper class to deal with any HTML document's `<head>` contents
---------------------

### Usage examples

#### Controller

	class Controller_Template extends Controller
	{
		protected $html_header = NULL;
		
		public function before()
		{
			$this->html_header = new HTML_Header();
			$this->html_header->set_title('New awesome page');
			$this->html_header->add(new HTML_Header_Stylesheet(array('file' => 'stylesheet.css'));
			$this->html_header->add(new HTML_Header_Stylesheet(array('file' => 'stylesheet_ie7.css', 'conditional' => 'lt IE 8'));
			$this->html_header->add(new HTML_Header_Javascript(array('file' => 'jquery.js')));
			$this->html_header->add(new HTML_Header_Meta('keywords', 'Awesome, kohana, KO3'));
			$this->html_header->add(new HTML_Header_Meta('description', 'This page shows how to be awesome'));
		}
	}

#### View

	<html>
		<head>
		<?php if (isset($html_header) && $html_header instanceof HTML_Header) echo $html_header->render(); ?>
		</hea>
	</html>
	<body>
	(...)

