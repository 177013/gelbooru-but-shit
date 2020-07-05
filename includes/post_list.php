<?php
//number of images/page
$limit = 30;
//number of pages to display. number - 1. ex: for 5 value should be 4
$page_limit = 10;
require "includes/header.php";?>
<script type="text/javascript">
    //<![CDATA[
    var posts = {};
    var pignored = {};
    //]]>
</script>
<div id="content">
    <div id="post-list">
        <div class="sidebar">
            <div class="space">
                <form action="index.php?page=search" method="post" class="post-search">
                    <input id="tags" name="tags" size="20" type="text"
                           class="post-search-input"
                           value="<?= isset($_GET['tags']) && $_GET['tags'] !== "all"
                               ? str_replace("%", '', str_replace("'", "&#039;", str_replace('"', '&quot;', $_GET['tags'])))
                               : '' ?>"/>
                    <input name="commit"
                           class="post-search-submit"
                           type="submit" value="Search"/>
                </form>
                <small class="post-search-help">(Supports wildcard *)</small>
            </div>
            <div class="space"></div>
            <div id="tag_list">
                <h5>Tags</h5>
                <ul class="tag-list">
                    <?php
                    /*Begining of tag listing on left side of site.
                    First let's get the current page we're on.	*/
                    if (isset($_GET['pid']) && $_GET['pid'] != "" && is_numeric($_GET['pid']) && $_GET['pid'] >= 0){
                        $page = $db->real_escape_string($_GET['pid']);
                    } else {
                        $page = 0;
                    }

                    $search = new search();
                    //No tags  have been searched for so let's check the last_update value to update our main page post count for parent posts. Updated once a day.
                    if (!isset($_GET['tags']) || (isset($_GET['tags']) && $_GET['tags'] === "all") || (isset($_GET['tags']) && $_GET['tags'] == "")) {
                        $query = "SELECT pcount, last_update FROM $post_count_table WHERE access_key='posts'";
                        $result = $db->query($query);
                        $row = $result->fetch_assoc();
                        $numrows = $row['pcount'];
                        $date = date("Ymd");
                        if ($row['last_update'] < $date) {
                            $query = "SELECT COUNT(id) FROM posts WHERE parent = '0'";
                            $result = $db->query($query);
                            $row = $result->fetch_assoc();
                            $numrows = $row['COUNT(id)'];
                            $query = "UPDATE $post_count_table SET pcount='" . $row['COUNT(id)'] . "', last_update='$date' WHERE access_key='posts'";
                            $db->query($query);
                        }
                    } else {
                        //Searched some tag, deal with page caching of html files.
                        $tags = $db->real_escape_string(str_replace("%", '', mb_trim(htmlentities($_GET['tags'], ENT_QUOTES, 'UTF-8'))));
                        $tags = explode(" ", $tags);
                        $tag_count = count($tags);
                        $new_tag_cache = urldecode($tags[0]);
                        $misc = new misc();
                        if (strpos(strtolower($new_tag_cache), "parent:") === false && strpos(strtolower($new_tag_cache), "user:") === false && strpos(strtolower($new_tag_cache), "rating:") === false && strpos($new_tag_cache, "*") === false) {
                            $new_tag_cache = $misc->windows_filename_fix($new_tag_cache);
                        }
                        if (isset($_GET['pid']) && is_numeric($_GET['pid']) && $_GET['pid'] > 0) {
                            $page = ($_GET['pid'] / $limit) + 1;
                        }
                        else {
                            $page = 0;
                        }
                        if ($tag_count > 1 || !is_dir(($main_cache_dir ?? '') . "" . "search_cache/" . $new_tag_cache . "/")
                            || !file_exists(($main_cache_dir ?? '') . "" . "search_cache/" . $new_tag_cache . "/" . $page . ".html") || strpos(strtolower($new_tag_cache), "all") !== false || strpos(strtolower($new_tag_cache), "user:") !== false || strpos(strtolower($new_tag_cache), "rating:") !== false || strpos($new_tag_cache, "-") === 0 || strpos(strtolower($new_tag_cache), "*") !== false || strpos(strtolower($new_tag_cache), "parent:") !== false) {
                            if (!is_dir(($main_cache_dir ?? '') . "" . "search_cache/")) {
                                @mkdir(($main_cache_dir ?? '') . "" . "search_cache");
                            }
                            $query = $search->prepare_tags(implode(" ", $tags));
                            $result = $db->query($query) or die($db->error);
                            $numrows = $result->num_rows;
                            $result->free_result();
                            if ($tag_count > 1 || strtolower($new_tag_cache) === "all" || strpos(strtolower($new_tag_cache), "user:") !== false || strpos(strtolower($new_tag_cache), "rating:") !== false || strpos($new_tag_cache, "-") === 0 || strpos(strtolower($new_tag_cache), "*") !== false || strpos(strtolower($new_tag_cache), "parent:") !== false) {
                                $no_cache = false;
                            }
                            else {
                                if (!is_dir(($main_cache_dir ?? '') . "" . "search_cache/" . $new_tag_cache . "/")) {
                                    @mkdir(($main_cache_dir ?? '') . "" . "search_cache/" . $new_tag_cache . "/");
                                }
                                $no_cache = true;
                            }
                        } else {
                            if (!is_dir(($main_cache_dir ?? '') . "" . "search_cache/")) {
                                mkdir(($main_cache_dir ?? '') . "" . "search_cache");
                            }
                            $tags = $new_tag_cache;
                            if (isset($_GET['pid']) && is_numeric($_GET['pid']) && $_GET['pid'] > 0) {
                                $page = ($_GET['pid'] / $limit) + 1;
                            }
                            else {
                                $page = 0;
                            }
                        }
                    }
                    //No images found
                    if ($numrows == 0) {
                        print '</ul></div></div><div class="content"><div><h1>Nobody here but us chickens!</h1>';
                    }
                    else {
                        if (isset($_GET['pid']) && $_GET['pid'] != "" && is_numeric($_GET['pid']) && $_GET['pid'] >= 0) {
                            $page = $db->real_escape_string($_GET['pid']);
                        }
                        else {
                            $page = 0;
                        }
                        if (!isset($_GET['tags']) || (isset($_GET['tags']) && $_GET['tags'] === "all") || (isset($_GET['tags']) && $_GET['tags'] == "")) {
                            $query = "SELECT id, image, directory, score, rating, tags, owner FROM $post_table WHERE parent = '0' ORDER BY id DESC LIMIT $page, $limit";
                        }
                        else {
                            if ($no_cache === true || $tag_count > 1 || strpos(strtolower($new_tag_cache), "user:") !== false || strpos(strtolower($new_tag_cache), "rating:") !== false || strpos($new_tag_cache, "-") === 0 || strpos(strtolower($new_tag_cache), "*") !== false || strpos(strtolower($new_tag_cache), "parent:") !== false) {
                                $query = $query . " LIMIT $page, $limit";
                            }
                        }
                        if (!isset($_GET['tags']) || $no_cache === true || $tag_count > 1 || strtolower($_GET['tags']) === "all" || strpos(strtolower($new_tag_cache), "user:") !== false || strpos(strtolower($new_tag_cache), "rating:") !== false || strpos($new_tag_cache, "-") === 0 || strpos(strtolower($new_tag_cache), "*") !== false || strpos(strtolower($new_tag_cache), "parent:") !== false) {
                            if ($no_cache === true) {
                                ob_start();
                            }

                            $gtags = array();
                            $images = '';
                            $tcount = 0;
                            $result = $db->query($query) or die($db->error);
                            //Limit main tag listing to 40 tags. Keep the loop down to the minimum really.
                            while ($row = $result->fetch_assoc()) {
								$post = new post();
                                $tags = mb_trim($row['tags']);
                                if ($tcount <= 40) {
                                    $ttags = explode(" ", $tags);
                                    foreach ($ttags as $current) {
                                        if ($current != "" && $current !== " ") {
                                            $gtags[$current] = $current;
                                            ++$tcount;
                                        }
                                    }
                                }

                                $images .= strtr('
                                    <span class="thumb">
                                        <a id="p:id" href=":href">
                                            <img src=":imgSrc" 
                                                 alt="post" 
                                                 data-has_children=":hasChildren"
                                                 title=":title"/>
                                        </a>', [
                                    ':id' => $row['id'],
                                    ':href' => 'index.php?' . http_build_query([
                                            'page' => 'post',
                                            's' => 'view',
                                            'id' => $row['id'],
                                        ]),
                                    ':imgSrc' => $thumbnailManager->makeIfNeeded($row['directory'] . '/' . $row['image']),
                                    ':hasChildren' => $post->has_children($row['id']),
                                    ':title' => $row['tags'] . ' score:' . $row['score'] . ' rating:' . $row['rating'],
                                ]);

                                $images .= '
                                    <script type="text/javascript">
                                    //<![CDATA[
                                    posts[' . $row['id'] . '] = {\'tags\':\'' . strtolower(str_replace('\\', "&#92;", str_replace("'", "&#039;", $tags))) . '\'.split(/ /g), \'rating\':\'' . $row['rating'] . '\', \'score\':' . $row['score'] . ', \'user\':\'' . str_replace('\\', "&#92;", str_replace(' ', '%20', str_replace("'", "&#039;", $row['owner']))) . '\'}
                                    //]]>
                    				</script>
                                ';

                                $images .= '</span>';

                                ++$tcount;
                            }
                            $result->free_result();
                            if (isset($_GET['tags']) && $_GET['tags'] != "" && $_GET['tags'] !== "all") {
                                $ttags = $db->real_escape_string(str_replace("'", "&#039;", $_GET['tags']));
                            }
                            else {
                                $ttags = "";
                            }
                            asort($gtags);
                            /*Tags have been sorted in ascending order
                            Let's now grab the index count from database
                            Needs to be escaped before query is sent!
                            URL Decode and entity decode for the links
                            */
                            foreach ($gtags as $current) {
                                $query = "SELECT index_count FROM $tag_index_table WHERE tag='" . $db->real_escape_string(str_replace("'", "&#039;", $current)) . "'";
                                $result = $db->query($query);
                                $row = $result->fetch_assoc();
                                $t_decode = urlencode(html_entity_decode($ttags, ENT_NOQUOTES, "UTF-8"));
                                $c_decode = urlencode(html_entity_decode($current, ENT_NOQUOTES, "UTF-8"));
                                ?>
                                <li class="tag-list-item">
                                    <a class="tag-list-button is-success" href="index.php?page=post&amp;s=list&amp;tags=<?= $t_decode ?>+<?= $c_decode ?>">+</a>
                                    <a class="tag-list-button is-danger" href="index.php?page=post&amp;s=list&amp;tags=<?= $t_decode ?>-<?= $c_decode ?>">-</a>
                                    <span class="tag-list-button">?</span>
                                    <span style="color: #a0a0a0;">
                                        <a class="tag-list-name" href="index.php?page=post&amp;s=list&amp;tags=<?= $c_decode ?>">
                                            <?= str_replace('_', ' ', $current) ?>
                                        </a>
                                        <?= $row['index_count'] ?>
                                    </span>
                                </li>
                                <?php
                            }
                            //Print out image results and filter javascript
                            echo '<li><br /><br /></li></ul></div></div><div class="content"><div>';
                            $images .= "</div><br /><br /><div style='margin-top: 550px; text-align: right;'><a id=\"pi\" href=\"#\" onclick=\"showHideIgnored('0','pi'); return false;\"></a></div><div id='paginator'>";
                            $images .= '<script type="text/javascript">
			//<![CDATA[
			filterPosts(posts)
			//]]>
			</script>';
                            echo $images;

                            //Pagination function. This should work for the whole site... Maybe.
                            $misc = new misc();
                            print $misc->pagination(
                                    $_GET['page'] ?? null,
                                    $_GET['s'] ?? null,
                                    $id ?? null,
                                    $limit,
                                    $page_limit,
                                    $numrows,
                                    $_GET['pid'] ?? null,
                                    $_GET['tags'] ?? null
                            );

                        }
                    }
                    ?>
            </div>
            <div id="footer"><a href="index.php?page=post&amp;s=add">Add</a> | <a href="help/">Help</a></div>
            <br/><br/>
        </div>
    </div>
</div></body></html>