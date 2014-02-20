HTML Header
====================

Helper class to deal with any HTML document's `<head>` contents
---------------------

Specifying second constructor parameter of `HTML_Header_Javascript()` object to `TRUE` forces script to be placed in
array of footer scripts. As `HTML_Header` renders HTML code that is valid only when placed within `<HEAD />` tag,
where footer scripts, the additional action has to be performed when user wants to display footer scripts. And to
get footer scripts one has to invoke `Html_Header::get_javascripts_footer()` method with optional parameter, which when set to TRUE will render the `<SCRIPT>` code instead of just returning array of defined scripts. Then place the result somewhere in template or other view.

### Usage examples

#### Controller

	class Controller_Template extends Controller
	{
		protected $html_header = NULL;
		
		public function before()
		{
			// Initialize the header
			$this->html_header = new HTML_Header();
			
			// Set the title
			$this->html_header->set_title('New awesome page');
			
			// Simple CSS attaching:
			$this->html_header->add(new HTML_Header_Stylesheet(array('file' => 'stylesheet.css'));
			
			// Example of wrapping CSS file into conditional comments:
			$this->html_header->add(new HTML_Header_Stylesheet(array('file' => 'stylesheet_ie7.css', 'conditional' => 'lt IE 8'));
			
			// Example of common JavaScript file attachment
			$this->html_header->add(new HTML_Header_Javascript(array('file' => 'jquery.js')));
			
			// Need a JavaScript somewhere else than in the <HEAD> section? Like just before closing </BODY>? set second parameter of HTML_Header_Javascript constructor to TRUE
			$this->html_header->add(new HTML_Header_Javascript(array('file' => 'footer_scripts.js', TRUE)));
			
			// Examples of <META> tag definitions:
			$this->html_header->add(new HTML_Header_Meta('keywords', 'Awesome, kohana, KO3'));
			$this->html_header->add(new HTML_Header_Meta('description', 'This page shows how to be awesome'));
			
			// This one will override another one, see last parameter. Useful when in need to overwrite description or keywords depending on the page/subpage content
			$this->html_header->set_meta(new HTML_Header_Meta('description', 'This META description replaces previous one'), 'description');
			
			// Use array instead of plain parameters for non-standard, (ex. HTML5) meta tag style definition:
			$this->html_header->add(new HTML_Header_Meta(array('charset' => 'utf-8')));
			// ...results in: <meta charset="utf-8" />
		}
	}

#### View

	<html>
		<head>
		<?php
		if (isset($html_header) && $html_header instanceof HTML_Header)
			echo $html_header->render();
		?>
		</hea>
	</html>
	<body>
	(...)

	<?php
	if (isset($html_header) && $html_header instanceof HTML_Header)
		echo $html_header->get_javascripts_footer(TRUE); // TRUE means get footer scripts rendered, ready to echo in the View/template.
	?>

	</body>
	</html>

