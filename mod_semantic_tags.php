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
// Include the syndicate functions only once
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'helper.php';
$jInput = new JInput();
$articleId = $jInput->getInt('id');
$cloud = ModSemanticTagsHelper::getSemanticTagsCloud($params, $articleId);
if (!$cloud) {
	return;
}
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');
require JModuleHelper::getLayoutPath('mod_semantic_tags', $params->get('layout', 'default'));

/*

print_r("<pre>");
print_r("\n");
print_r($pull);
print_r("\n");die();

*/
