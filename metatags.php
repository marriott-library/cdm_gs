<?php
	// Author:       Alan Witkowski
	// Description:  Outputs metatags in the HTML header for Google Scholar.
	// Requirements: ContentDM 6, Access to ContentDM website configuration tool. Field names will need to be prefixed
	//               with citation_ and author names should be semicolon delimited.
	// Usage:        First modify the line below that says $description_nick = "descri". This determine's what field to use
	//               as your description. To find the nickname, log into the ContentDM Admininstration
	//               (e.g. http://yourcontentdmserver:port/cgi-bin/admin/start.exe), view the fields for your collection, and click edit
	//               for your description field. The URL for the edit page will contain CISONICK=nick. This is the nickname for the field.
	//
	//               Next, Log in to your contentDM's website configuration tool (e.g. http://yourcontentdmserver/config/configtool/
	//               Under Global Settings click Custom Pages/Scripts, then click Custom Scripts. Click the browse button for
	//               "Upload Top Includes". Upload this php file, then hit "save changes" and "publish".

	// Change this value to the nickname of your description field
	$description_nick = "descri";

    // Check for item info
    if(isset($this->itemInfo)) {

        // Get description
        $meta_description = (string)$this->itemInfo->$description_nick;
        if($meta_description != "") {
            $meta_description = str_replace("\"", "", $meta_description);
            echo "\t<meta name=\"description\" content=\"" . $meta_description . "\" />\n";
        }

        // Iterate through fields
        foreach($this->fieldsInfo as $key => $field) {

            // grab fields that start with citation_
            if(strpos($field, "citation_") !== false) {
                $data = (string)$this->itemInfo->$key;
                if($data != "") {

                    // Split up authors by semicolon
                    if($field == "citation_author") {
                        $author_array = explode(";", $data);
                        foreach($author_array as $author) {
                            $author = trim($author);
                            echo "\t<meta name=\"citation_author\" content=\"$author\" />\n";
                        }
                    }
                    else {
                        // 'field' is the field name, 'data' is the field data
                        echo "\t<meta name=\"$field\" content=\"$data\" />\n";
                    }
                }
            }
        }

		// Output pdf link
        $hostname = $_SERVER["HTTP_HOST"];
        echo "\t<meta name=\"citation_pdf_url\" content=\"http://$hostname{$this->bigPDFUrl}\" />\n";
    }
?>
