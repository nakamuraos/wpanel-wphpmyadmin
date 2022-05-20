<?php define('_PHONHO_HPA', true);

    include_once 'incfiles/function.php';

    if (IS_LOGIN) {
        $title = 'Sửa tập tin';
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $page = $page <= 0 ? 1 : $page;

        include_once 'incfiles/header.php';

        echo '<div class="title">' . $title . '</div>';

        if ($dir == null || $name == null || !is_file(processDirectory($dir . '/' . $name))) {
            echo '<div class="list"><span>Đường dẫn không tồn tại</span></div>
            <div class="title">Chức năng</div>
            <ul class="list">
                <li><img src="icon/list.png"/> <a href="index.php' . $pages['paramater_0'] . '">Danh sách</a></li>
            </ul>';
        } else if (!isFormatText($name) && !isFormatUnknown($name)) {
            echo '<div class="list"><span>Tập tin này không phải dạng văn bản</span></div>
            <div class="title">Chức năng</div>
            <ul class="list">
                <li><img src="icon/list.png"/> <a href="index.php?dir=' . $dirEncode . $pages['paramater_1'] . '">Danh sách</a></li>
            </ul>';
        } else {
            $total = 0;
            $index = ($page * $configs['page_file_edit']) - $configs['page_file_edit'];
            $dir = processDirectory($dir);
            $path = $dir . '/' . $name;
            $content = file_get_contents($path);
            $pageLine = $configs['page_file_edit'];
            $notice = null;

            if (isset($_POST['s_save'])) {
                if ((empty($_POST['content']) && strlen($content) <= 0 && $pageLine > 0) || (empty($_POST['content']) && $pageLine <= 0)) {
                    $notice = '<div class="notice_failure">Chưa nhập nội dung</div>';
                } else {
                    if ($pageLine > 0) {
                        $content = str_replace("\r\n", "\n", $content);
                        $content = str_replace("\r", "\n", $content);

                        if (strpos($content, "\n") !== false) {
                            $ex = explode("\n", $content);
                            $count = count($ex);
                            $end = $index + $configs['page_file_edit'] <= $count ? $index + $configs['page_file_edit'] : $count;
                            $content = null;

                            if ($index > 0)
                                for ($i = 0; $i < $index; ++$i)
                                    $content .= $ex[$i] . "\n";

                            $content .= str_replace("\r", "\n", str_replace("\r\n", "\n", trim($_POST['content'])));

                            if ($page < ceil($count / $configs['page_file_edit']))
                                for ($i = $end; $i < $count; ++$i)
                                    $content .= "\n" . $ex[$i];
                        } else {
                            $content = trim($_POST['content']);
                        }
                    } else {
                        $content = trim($_POST['content']);
                        $content = str_replace("\r\n", "\n", $content);
                        $content = str_replace("\r", "\n", $content);
                    }

                    if (file_put_contents($path, $content)) {
                        $notice = '<div class="notice_succeed">Lưu lại thành công</div>';
                    } else {
                        $notice = '<div class="notice_failure">Lưu lại thất bại</div>';
                        $content = file_get_contents($path);
                    }
                }
            }

            if (strlen($content) > 0) {
                $content = str_replace("\r\n", "\n", $content);
                $content = str_replace("\r", "\n", $content);

                if ($pageLine > 0 && strpos($content, "\n") !== false) {
                    $ex = explode("\n", $content);
                    $count = count($ex);

                    if ($count > $configs['page_file_edit']) {
                        $content = null;
                        $total = ceil($count / $configs['page_file_edit']);
                        $end = $index + $configs['page_file_edit'] <= $count ? $index + $configs['page_file_edit'] : $count;

                        for ($i = $index; $i < $end; ++$i) {
                            if ($i >= $end - 1)
                                $content .= $ex[$i];
                            else
                                $content .= $ex[$i] . "\n";
                        }
                    }
                }
            }

            echo $notice;
            echo '<div class="list ellipsis break-word">
                <span class="bull">&bull;</span> Tập tin: <strong class="file_name_edit">' . $name . '</strong><hr/>
                <form action="edit_text.php?dir=' . $dirEncode . '&name=' . $name . $pages['paramater_1'] . ($page > 1 ? '&page=' . $page : null) . '" method="post">
                    <span class="bull">&bull;</span> Nội dung:<br/>
                    <div class="parent_box_edit">
                        <textarea class="box_edit" name="content" rows="10">' . htmlspecialchars($content) . '</textarea>
                    </div>
                    <div class="search_replace search">
                        <span class="bull">&bull;</span> Tìm kiếm:<br/>
                        <input type="text" name="search" value=""/>
                    </div>
                    <div class="search_replace replace">
                        <span class="bull">&bull;</span> Thay thế:<br/>
                        <input type="text" name="replace" value=""/>
                    </div>
                    <div class="input_action">
                        <input type="submit" name="s_save" value="Lưu lại"/>
                    </div>
                </form>';

                if ($pageLine > 0 && $total > 1)
                    echo page($page, $total, array(PAGE_URL_DEFAULT => 'edit_text.php?dir=' . $dirEncode . '&name=' . $name . $pages['paramater_1'], PAGE_URL_START => 'edit_text.php?dir=' . $dirEncode . '&name=' . $name . $pages['paramater_1'] . '&page='));

            echo '</div>
            <div class="title">Chức năng</div>
            <ul class="list">
                <li><img src="icon/edit_text_line.png"/> <a href="edit_text_line.php?dir=' . $dirEncode . '&name=' . $name . $pages['paramater_1'] . '">Sửa theo dòng</a></li>
                <li><img src="icon/download.png"/> <a href="file_download.php?dir=' . $dirEncode . '&name=' . $name . $pages['paramater_1'] . '">Tải về</a></li>
                <li><img src="icon/info.png"/> <a href="file.php?dir=' . $dirEncode . '&name=' . $name . $pages['paramater_1'] . '">Thông tin</a></li>
                <li><img src="icon/rename.png"/> <a href="file_rename.php?dir=' . $dirEncode . '&name=' . $name . $pages['paramater_1'] . '">Đổi tên</a></li>
                <li><img src="icon/copy.png"/> <a href="file_copy.php?dir=' . $dirEncode . '&name=' . $name . $pages['paramater_1'] . '">Sao chép</a></li>
                <li><img src="icon/move.png"/> <a href="file_move.php?dir=' . $dirEncode . '&name=' . $name . $pages['paramater_1'] . '">Di chuyển</a></li>
                <li><img src="icon/delete.png"/> <a href="file_delete.php?dir=' . $dirEncode . '&name=' . $name . $pages['paramater_1'] . '">Xóa</a></li>
                <li><img src="icon/access.png"/> <a href="file_chmod.php?dir=' . $dirEncode . '&name=' . $name . $pages['paramater_1'] . '">Chmod</a></li>
                <li><img src="icon/list.png"/> <a href="index.php?dir=' . $dirEncode . $pages['paramater_1'] . '">Danh sách</a></li>
            </ul>';
        }

        include_once 'incfiles/footer.php';
    } else {
        goURL('login.php');
    }