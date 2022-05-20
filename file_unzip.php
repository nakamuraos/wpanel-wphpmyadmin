<?php define('_PHONHO_HPA', true);

    include_once 'incfiles/function.php';

    if (IS_LOGIN) {
        $title = 'Giải nén tập tin';
        $format = $name == null ? null : getFormat($name);

        include_once 'incfiles/header.php';

        echo '<div class="title">' . $title . '</div>';

        if ($dir == null || $name == null || !is_file(processDirectory($dir . '/' . $name))) {
            echo '<div class="list"><span>Đường dẫn không tồn tại</span></div>
            <div class="title">Chức năng</div>
            <ul class="list">
                <li><img src="icon/list.png"/> <a href="index.php' . $pages['paramater_0'] . '">Danh sách</a></li>
            </ul>';
        } else if (!in_array($format, array('zip', 'jar'))) {
            echo '<div class="list"><span>Tập tin không phải zip</span></div>
            <div class="title">Chức năng</div>
            <ul class="list">
                <li><img src="icon/list.png"/> <a href="index.php?dir=' . $dirEncode . $pages['paramater_1'] . '">Danh sách</a></li>
            </ul>';
        } else {
            $dir = processDirectory($dir);
            $format = getFormat($name);

            if (isset($_POST['submit'])) {
                echo '<div class="notice_failure">';

                if (empty($_POST['path'])) {
                    echo 'Chưa nhập đầy đủ thông tin';
                } else if (!is_dir(processDirectory($_POST['path']))) {
                    echo 'Đường dẫn giải nén không tồn tại';
                } else {
                    include 'pclzip.class.php';

                    $zip = new PclZip($dir . '/' . $name);

                    if (!$zip->extract(PCLZIP_OPT_PATH, processDirectory($_POST['path'])))
                        echo 'Giải nén tập tin lỗi';
                    else if (isset($_POST['is_delete']))
                        @unlink($dir . '/' . $name);

                    goURL('index.php?dir=' . $dirEncode . $pages['paramater_1']);
                }

                echo '</div>';
            }

            echo '<div class="list">
                <form action="file_unzip.php?dir=' . $dirEncode . '&name=' . $name . $pages['paramater_1'] . '" method="post">
                    <span class="bull">&bull;</span>Đường dẫn giải nén:<br/>
                    <input type="text" name="path" value="' . $dir . '" size="18"/><br/>
                    <input type="checkbox" name="is_delete" value="1"/> Xóa tập tin zip<br/>
                    <input type="submit" name="submit" value="Giải nén"/>
                </form>
            </div>
            <div class="title">Chức năng</div>
            <ul class="list">
                <li><img src="icon/info.png"/> <a href="file.php?dir=' . $dirEncode . '&name=' . $name . $pages['paramater_1'] . '">Thông tin</a></li>
                <li><img src="icon/unzip.png"/> <a href="file_viewzip.php?dir=' . $dirEncode . '&name=' . $name . $pages['paramater_1'] . '">Xem</a></li>
                <li><img src="icon/download.png"/> <a href="file_download.php?dir=' . $dirEncode . '&name=' . $name . $pages['paramater_1'] . '">Tải về</a></li>
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

?>