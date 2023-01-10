<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gia Phả Họ Đoàn (Biệt Phái)</title>
    <script src="./asset/js/familytree.js"></script>
    <script src="./asset/js/jquery.js"></script>
    
</head>

<body>
<div id="editForm"
    style="display:none; text-align:center; position:absolute; border: 1px solid #aeaeae;width:300px;background-color:#F57C00;z-index:10000; ">
    <div id="editForm_title" style="padding: 10px 0 10px 0; background-color:#039BE5; color: #ffffff;">Edit Form</div>
    <div>
        <div style="padding: 10px 0 5px 0;">
            <label style="color:#ffffff; width:50px; display:inline-block;" for="name">id</label>
            <input style="background-color:#FFCA28" id="id" value="" />
        </div>
        <div style="padding: 5px 0 10px 0;">
            <label style="color:#ffffff; width:50px; display:inline-block;" for="title">name</label>
            <input style="background-color:#FFCA28"  id="name" value="" />
        </div>
        <div style="padding: 5px 0 10px 0;">
            <label style="color:#ffffff; width:50px; display:inline-block;" for="title">Id Father</label>
            <input style="background-color:#FFCA28"  id="id_father" value="" />
        </div>
        <div style="padding: 5px 0 10px 0;">
            <label style="color:#ffffff; width:50px; display:inline-block;" for="title">Id Mother</label>
            <input style="background-color:#FFCA28"  id="id_mother" value="" />
        </div>
        <div style="padding: 5px 0 10px 0;">
            <label style="color:#ffffff; width:50px; display:inline-block;" for="title">Id Couple</label>
            <input style="background-color:#FFCA28"  id="id_couple" value="" />
        </div>
        <div style="padding: 5px 0 15px 0;">
            <button style="width:108px;" id="cancel">Cancel</button>
            <button style="width:108px;" id="save">Save</button>
        </div>
    </div>
</div>
    <div style="width:100%; height:auto;" id="tree">
    </div>
</body>
<script src="./asset/js/main.js"></script>

</html>