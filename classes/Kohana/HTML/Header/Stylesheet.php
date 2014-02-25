<?php defined('SYSPATH') or die('No direct script access.');
/**
 * A single javascript entity for HTML Header
 * @author Maciej Kwiatkowski <maciej.kwiatkowski@unicorn.net.pl>
 * @package Super Kohana
 * @category HTML Header
 */

class Kohana_HTML_Header_Stylesheet {

	protected
		$file = NULL,
		$attributes = array(),
		$protocol = NULL,
		$index = FALSE,
		$conditional = NULL;
			
	/**
	 * @var null|string|View
	 */
	protected $inline = NULL;

	/**
	 * @param string|array|View $file
	 * @param bool $inline Set to TRUE for placing INLINE code instead of linking to a file. First argument becomes the text content in this case
	 */
	public function __construct($file, $inline = FALSE)
	{
		if ($inline OR $file instanceof View)
		{
			$this->inline = $file;
		}
		elseif (is_array($file))
		{
			$this->file = Arr::get($file, 'file');
			$this->attributes = Arr::get($file, 'attributes');
			$this->protocol = Arr::get($file, 'protocol');
			$this->index = Arr::get($file, 'index');
			$this->conditional = Arr::get($file, 'conditional');
		}
		else
		{
			$this->file = (string) $file;
		}
	}
	
	public function __toString()
	{
		$html = "";
		if ( ! empty($this->conditional))
		{
			$html .= "<!--[if ".$this->conditional."]>\n";
		}
		
		if (isset($this->inline))
			$html .= "<style type='text/css'>".$this->inline."</style>\n";
		else
			$html .= HTML::style($this->file, $this->attributes, $this->protocol, $this->index)."\n";
		
		if ( ! empty($this->conditional))
		{
			$html .= "<![endif]-->";
		}

		return $html;
	}
}
