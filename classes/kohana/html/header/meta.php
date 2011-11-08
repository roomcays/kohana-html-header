<?php defined('SYSPATH') or die('No direct script access.');
/**
 * A single javascript entity for HTML Header
 * @author Maciej Kwiatkowski <maciek@unicorn.net.pl>
 * @package Super Kohana
 * @category HTML Header
 */

class Kohana_HTML_Header_Meta {
	protected
		$attributes = array();
	
	/**
	 * Header meta tag constructor
	 * @param string|array $name Examples: 'keywords', 'description', 'copyright', or an associative array ('name' => 'keywords', 'content' => 'example keyword list')  
	 * @param string $content
	 * @throws Kohana_Exception
	 */
	public function __construct($name, $content = NULL)
	{
		if (is_array($name))
		{
			$this->attributes = $name;
		}
		else if ( ! empty($content))
		{
			$this->attributes['name'] = (string) $name;
			$this->attributes['content'] = (string) $content;
		}
		else
		{
			throw new Kohana_Exception('Meta tag requires at least name and content attributes. Specify array as first parameter for more flexibility.');
		}
	}

	public function __toString()
	{
		return "<meta".HTML::attributes($this->attributes)." />";
	}
}
