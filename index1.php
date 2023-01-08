<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phả Hệ Họ Đoàn</title>
    <link rel="stylesheet" href="./asset/css/style.css">
    <script src="./asset/js/jquery.js"></script>
</head>

<body>
    <?php
    include_once('./backed/model/Person.php');
    function hasChild($id)
    {
        echo '<div class="tree">';
        // Create connection
        $conn = new Person();
        $children = $conn->getListPersonWithParentId($id);
        // return $rs->fetch_all(MYSQLI_ASSOC);
        if (count($children) > 0) {
            echo '<ul>';
            foreach ($children as $row) {
    ?>
                <li>
                    <a href="#">
                        <?php if(true):?>
                            <input id="<?= $row["id"] ?>" type="text" value="<?= $row["name"] ?>">
                        <?php else:?>
                            <?= $row["name"] ?>
                        <?php endif;?>
                <script>
                        $('#<?= $row["id"]?>').change(function () {
                            console.log('id', $(this)[0].id);
                            console.log('value', $(this)[0].value);
                            $.ajax({
                                url: "/backed/api.php",
                                method:'POST',
                                data: {
                                    id: $(this)[0].id,
                                    value: $(this)[0].value,
                                    type: 'update',
                                },
                                success: function (data) {
                                    data = JSON.parse(data);
                                    if (data.status) {
                                        alert(data.message);
                                    } else {
                                        alert(data.message);
                                    }
                                }
                                // beforeSend: function( xhr ) {
                                //     xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
                                // }
                            }).done(function( data ) {
                                // console.log( "Sample of data:", data );
                            });
                            
                        });
                </script>
                    </a>
                    <a><input id="add_wife_husband_<?= $row["id"] ?>" type="text" placeholder="wife/husband"></a>
                    <?php hasChild($row["id"]) ?>
                </li>
                <?php
            }
            
            echo '<li><a href="#"><input id="add_child_'.$id.'" type="text" placeholder="add child"></a></li>';
            echo '</ul>';
        } else {

        }
        echo '</div>';
    }
    ?>
    <?php hasChild(0); ?>
</body>

</html>