<?php
/**
 * @version     1.0.0
 * @package     Semantic Cloud
 * @subpackage  mod_semantic_tags
 * @copyright   Copyright (C) 2017. All rights reserved.
 * @license     GNU General Public License version 2 or later
 * @author      Francisc Tar <francisc@icontentweb.com> - http://icontentweb.com
 * @since       1.0.0
 */

// No direct access
defined('_JEXEC') or die;

class ModSemanticTagsHelper
{
	/**
	 * @param $articleId
	 *
	 * @return mixed
	 *
	 * @since 1.0.0
	 */
	private static function getArticleContent($articleId)
    {
	    // Obtain a database connection
	    $db = JFactory::getDbo();
	    $query = $db->getQuery(true)
		    ->select($db->quoteName('title'))
		    ->select($db->quoteName('introtext'))
		    ->select($db->quoteName('fulltext'))
		    ->from($db->quoteName('#__content'))
		    ->where('id = '. (int) $articleId);
        $db->setQuery($query);
        $result = $db->loadRow();
        return $result;
    }

    public static function getSemanticTagsCloud($params, $articleId)
    {
    	$cloud = null;
	    if ($articleId) {

		    $http = new JHttp();
		    $headers = [
			    'Content-Type' => 'application/json',
			    'Authorization' => 'Bearer ' . $params->get('access_token', ''),
					'Origin' => JURI::base(),
		    ];
		    $uri = JUri::getInstance();
		    $data = [
			    'article_id' => $articleId,
			    'page_url' => $uri->toString(),
			    'website' => JURI::base(),
			    'content' => '',
		    ];
		    foreach (self::getArticleContent($articleId) as $item) {
			    if ($item) {
				    $data['content'] .= ' ' . $item;
			    }
		    }
		    $data = json_encode($data);
				try {
			    $pull = $http->post('https://api.icontentweb.com/tags', $data, $headers, 30);
			    $body = json_decode($pull->body, true);
			    if ($body && key_exists('tags', $body)) {
				    $cloud = $body['tags'];
				    $document = JFactory::getDocument();
				    $document->addStyleSheet(JUri::base() . 'modules/mod_semantic_tags/css/mod_semantic_tags.css');
				    $document->addScript(JUri::base() . 'modules/mod_semantic_tags/js/mod_semantic_tags.js');
			    }
				} catch (\Exception $e) {
					#return 'Error:' . $e->getMessage();
					return null;
				}
	    }
	    return $cloud;
    }
}
