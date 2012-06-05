HTML Header
====================

Helper class to deal with any HTML document's `<head>` contents
---------------------

Specifying second constructor parameter of `HTML_Header_Javascript()` object to `TRUE` forces script to be placed in
array of footer scripts. As `HTML_Header` renders HTML code that is valid only when placed within `<HEAD />` tag,
where footer scripts, the additional action has to be performed when user wants to display footer scripts. And to
get footer scripts one has to invoke `Html_Header::get_javascripts_footer()` method. Then place the result somewhere in
template or other view.

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
			$this->html_header->add(new HTML_Header_Javascript(array('file' => 'footer_scripts.js', TRUE)));
			$this->html_header->add(new HTML_Header_Meta('keywords', 'Awesome, kohana, KO3'));
			$this->html_header->add(new HTML_Header_Meta('description', 'This page shows how to be awesome'));

			$this->html_header->set_meta(new HTML_Header_Meta('description', 'This META description replaces previous one'), 'description');
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

	<?php if (isset($html_header) && $html_header instanceof HTML_Header) echo $html_header->get_javascripts_footer(); ?>

	</body>
	</html>

