INSERT INTO `oc_extension_install` (`extension_install_id`, `extension_download_id`, `filename`, `date_added`) VALUES
(2, 0, 'plaza_engine.ocmod.zip', '2021-01-19 16:10:41');

INSERT INTO `oc_modification` (`modification_id`, `extension_install_id`, `name`, `code`, `author`, `version`, `link`, `xml`, `status`, `date_added`) VALUES
(2, 2, 'Plaza Engine', 'plaza_engine', 'Plaza Theme', '1.0', '', '<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n<modification>\r\n    <code>plaza_engine</code>\r\n    <name>Plaza Engine</name>\r\n    <version>1.0</version>\r\n    <author>Plaza Theme</author>\r\n\r\n    <!-- Menu Left in Admin -->\r\n    <file path=\"admin/controller/common/column_left.php\">\r\n        <operation>\r\n            <search ><![CDATA[$catalog = array();]]></search>\r\n            <add position=\"before\"><![CDATA[\r\n            // Plaza Engine\r\n            $this->load->language(\'plaza/engine\');\r\n\r\n            if($this->user->hasPermission(\'access\', \'plaza/engine\')) {\r\n                $data[\'menus\'][] = array(\r\n                    \'id\'       => \'plaza-dashboard\',\r\n                    \'icon\'     => \'fa-plaza\',\r\n                    \'name\'     => $this->language->get(\'text_plaza_engine\'),\r\n                    \'href\'     => $this->url->link(\'plaza/engine\', \'user_token=\' . $this->session->data[\'user_token\'], true)\r\n                );\r\n            }\r\n            ]]></add>\r\n        </operation>\r\n    </file>\r\n</modification>', 1, '2021-01-19 16:10:41');