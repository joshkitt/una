<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Groups',
    'version_from' => '9.0.11',
    'version_to' => '10.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '10.0.0-B1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/groups/updates/update_9.0.11_10.0.0/',
    'home_uri' => 'groups_update_9011_1000',

    'module_dir' => 'boonex/groups/',
    'module_uri' => 'groups',

    'db_prefix' => 'bx_groups_',
    'class_prefix' => 'BxGroups',

    /**
     * Transcoders.
     */
    'transcoders' => array(
        'bx_groups_avatar_big'
    ),

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
        'execute_sql' => 1,
        'update_files' => 1,
        'update_languages' => 1,
        'register_transcoders' => 1,
        'clear_db_cache' => 1,
    ),

    /**
     * Category for language keys.
     */
    'language_category' => 'Groups',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
