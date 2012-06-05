<?php defined('SYSPATH') or die('No direct script access.');
/**
 * This module deals with stuff that goes into the HTML header
 * section, like stylesheets, javascripts, favicons, meta-data, etc.
 * @author Maciej Kwiatkowski <maciej.kwiatkowski@unicorn.net.pl>
 * @author Wojciech Duda <wduda@unicorn.net.pl>
 * @package Super Kohana
 * @category HTML Header
 */

class Kohana_HTML_Header {
	
	protected
		$config,
		$title = NULL,
		$meta = array(),
		$stylesheets = array(),
		$javascripts = array(),
		$favicons = array();

	protected static $instance = NULL;

	/**
	 * Returns existing instance of HTML_Header or creates and return new
	 *
	 * @static
	 * @return Html_Header
	 */
	public static function instance()
	{
		if (Html_Header::$instance === NULL)
		{
			Html_Header::$instance = new Html_Header();
		}
		
		return Html_Header::$instance;
	}

	/**
	 * Returns existsing instance of HTML_Header
	 *
	 * @deprecated	Just use instance() instead
	 * @static
	 * @return Html_Header
	 */
	public static function get_instance()
	{
		return Html_Header::$instance;
	}

	/**
	 * Loads configuration for HTML_Header instance (config/html_header.php)
	 * Configuration holds general HTML_Header settings, like name of the view,
	 * but DOES NOT hold any sets of HTML_Header's children, i.e. metas, javascripts
	 * CSS stylesheets or title.
	 */
	public function __construct()
	{
		$this->config = Kohana::$config->load('html_header');
	}

	/**
	 * Renders the HTML_Header
	 *
	 * @return string|null
	 */
	public function __toString()
	{
		try
		{
			return $this->render()->render();
		}
		catch (Exception $e)
		{
			// Display the exception message
			Kohana::exception_handler($e);
			return NULL;
		}
	}
	
	/**
	 * Adds an item to the header object.
	 * 
	 * @param mixed	$item	The item to be added
	 * @param bool	$reset	Reset (empty) the array of the item type before adding
	 * @return int|null 	Number of elements of given type after adding
	 */
	public function add($item, $reset = FALSE)
	{
		switch ($item)
		{
			case is_array($item):
				$i = 0;
				foreach ($item as $_item)
				{
					$this->add($_item, ($i == 0) ? $reset : FALSE);
					$i++;
				}
				break;
			case $item instanceof Html_Header_Stylesheet:
				return ($reset) ? $this->stylesheets = array($item) : array_push($this->stylesheets, $item);
				break;
			case $item instanceof Html_Header_Javascript:
				return ($reset) ? $this->javascripts = array($item) : array_push($this->javascripts, $item);
				break;
			case $item instanceof Html_Header_Meta:
				return ($reset) ? $this->meta = array($item) : array_push($this->meta, $item);
				break;
			case $item instanceof Html_Header_Favicon:
				return ($reset) ? $this->favicons = array($item) : array_push($this->favicons, $item);
				break;
			default:
				$this->resolve_unknown_type($item, $reset);
				break;
		}
	}

	/**
	 * Adds new item replacing existing one
	 *
	 * @param $item	mixed	One of the HTML_Header_* or array of these
	 * @return int|null
	 */
	public function set($item)
	{
		return $this->add($item, TRUE);
	}
	
	/**
	 * Renders the HTML code using specified view.
	 * If no view is specified, loads the default one specified in module configuration.
	 *
	 * @uses View::factory()
	 * @param string		$view	View location
	 * @return View|NULL			Rendered View, that includes title, metas, favicons, stylesheets and javascripts
	 */
	public function render($view = NULL)
	{
		try
		{
			$view = View::factory(empty($view) ? $this->config->get('view') : $view);
			$view
				->set('title', $this->title)
				->set('meta', $this->meta)
				->set('favicons', $this->favicons)
				->set('stylesheets', $this->stylesheets)
				->set('javascripts', $this->javascripts);
			
			return $view;
		}
		catch (Exception $e)
		{
			// Display the exception message
			Kohana::exception_handler($e);
			return NULL;
		}
	}
	
	/**
	 * Resolves the file type (css, js) regarding to it's
	 * source file extension.
	 *
	 * @uses File::get_extension()
	 * @param string $item
	 * @param bool $reset	Reset (empty) the array of the item type before adding
	 * @return int			Number of elements of given type after adding
	 */
	protected function resolve_unknown_type($item, $reset = FALSE)
	{
		$ext = File::get_extension($item);
		switch (strtolower($ext))
		{
			case 'css':
				return $this->add(new HTML_Header_Stylesheet($item), $reset);
				break;
			case 'js':
				return $this->add(new HTML_Header_Javascript($item), $reset);
				break;
			case 'ico':
				return $this->add(new HTML_Header_Favicon($item), $reset);
				break;
		}
	}
	
	/**
	 * Sets the page title overwriting any existing one
	 * Strips HTML tags and encodes HTML special chars
	 *
	 * @uses HTML::chars()
	 * @param string $title
	 */
	public function set_title($title)
	{
		$this->title = HTML::chars(strip_tags($title));
	}
	
	/**
	 * Adds additional title to an existing one (e.g. for subpages)
	 * $before parameter adds new title BEFORE an existing one
	 * Note the default separator.
	 *
	 * @uses HTML::chars()
	 * @param string $title
	 * @param string $separator
	 * @param bool $before
	 */
	public function add_title($title, $separator = " - ", $before = FALSE)
	{
		if ($before)
		{
			$this->title = HTML::chars(strip_tags($title)).( ! empty($this->title) ? $separator.$this->title : "");
		}
		else
		{
			$this->title = ( ! empty($this->title) ? $this->title.$separator : "").HTML::chars(strip_tags($title));
		}
	}
	
	/**
	 * Sets the page language via the meta tag
	 *
	 * @param string $language	Examples: en-us, pl
	 * @return int				Number of META elements after adding
	 */
	public function set_language($language)
	{
		return array_push($this->meta, new HTML_Header_Meta(array('http-equiv' => 'Content-Language',  'content' => (string) $language)));
	}
	
	/**
	 * Sets the page character set
	 *
	 * @param string	$charset	Examples: UTF-8
	 * @return integer				Number of elements of given type after adding
	 */
	public function set_charset($charset)
	{
		return array_push($this->meta, new HTML_Header_Meta(array('http-equiv' => 'Content-Type',  'content' => 'text/html; charset='.(string) $charset)));
	}

	/**
	 * As the there are many META items, that are identified by NAME attribute
	 * this methods allow to overwrite an existing one (if $name attribute given)
	 * with the new.
	 * Otherwise adds META element in the regular way.
	 *
	 * @param $meta_item
	 * @param null $name
	 * @return array|int|null
	 */
	public function set_meta($meta_item, $name = NULL)
	{
		if (is_null($name))
		{
			return $this->set($meta_item);
		}
		else
		{
			// ex.: name="description" ($name = 'name', $value = 'description')
			$value = $meta_item->attribute($name);
			$i = 0;
			foreach ($this->meta as &$meta)
			{
				if ($meta->attribute($name) === $value)
				{
					if ($i == 0)
					{
						$meta = $meta_item;
						$i++;
					}
					else
					{
						unset($meta);
					}
				}
			}
			return ($i == 0) ? $this->set($meta_item) : $this->meta;
		}

	}
}
