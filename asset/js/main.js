
//     nodes: is the data source. The 'id' property is mandatory.

// pids: are the partner ids, represents connection between two partners (wife and husband).

// mid: mother id.

// fid: father id.

// gender: male or female.

// nodeBinding: 'name' property form the data source will be bound to 'field_0' ui element from the template.

function getRndInteger(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}
var editForm = function () {
    this.nodeId = null;
};

editForm.prototype.init = function (obj) {
    var that = this;
    this.obj = obj;
    this.editForm = document.getElementById("editForm");
    this.idInput = document.getElementById("id");
    this.nameInput = document.getElementById("name");
    this.idFather = document.getElementById("id_father");
    this.idMother = document.getElementById("id_mother");
    this.idCouple = document.getElementById("id_couple");
    this.cancelButton = document.getElementById("cancel");
    this.saveButton = document.getElementById("save");

    this.cancelButton.addEventListener("click", function () {
        that.hide();
    });

    this.saveButton.addEventListener("click", function () {
        console.log('save nè');

        var node = family.get(that.nodeId);
        node.name = that.nameInput.value;
        node.id = that.idInput.value;
        node.fid = that.idFather.value;
        node.mid = that.idMother.value;
        node.pids = [that.idCouple.value];

        family.updateNode(node);
        that.hide();
    });
};

editForm.prototype.show = function (nodeId) {
    this.hide();
    this.nodeId = nodeId;

    var left = document.body.offsetWidth / 2 - 150;
    this.editForm.style.display = "block";
    this.editForm.style.left = left + "px";
    document.getElementById('editForm_title').innerHTML = 'Edit'

    var node = family.get(nodeId);

    this.nameInput.value = node.name;
    this.idInput.value = node.id;
    this.idFather.value = node.fid;
    this.idMother.value = node.mid;
    this.idCouple.value = node.pids;
};

editForm.prototype.hide = function (showldUpdateTheNode) {
    this.editForm.style.display = "none";
};

var node;
var family = new FamilyTree(document.getElementById("tree"), {
    mouseScrool: FamilyTree.action.none,
    nodeMenu: {
        edit: { text: "Edit" },
        addFather: {
            text: "Add Father", onClick: function (nodeID) {
                console.log('nodeid', nodeID);
                add(nodeID, 'addFather');
            }
        },
        addMother: {
            text: "Add Mother", onClick: function (nodeID) {
                console.log('nodeid', nodeID);
                add(nodeID, 'addMother');
            }
        },
        addHusband: {
            text: "Add Husband", onClick: function (nodeID) {
                console.log('nodeid', nodeID);
                add(nodeID, 'addHusband');
            }
        },
        addWife: {
            text: "Add Wife", onClick: function (nodeID) {
                console.log('nodeid', nodeID);
                add(nodeID, 'addWife');
            }
        },
        addChildMale: {
            text: "Add Childe Male", onClick: function (nodeID) {
                console.log('nodeid', nodeID);
                add(nodeID, 'addChildMale');
            }
        },
        addChildFemale: {
            text: "Add Child Female", onClick: function (nodeID) {
                console.log('nodeid', nodeID);
                add(nodeID, 'addChildFemale');
            }
        },
    },
    editUI: new editForm(),
    nodeBinding: {
        field_0: "id",
        field_1: "name",
    },

    // nodes: 
});
$(document).ready(async function () {
    await $.ajax({
        url: "/backend/api.php",
        method: 'GET',
        data: {
            id: $(this)[0].id,
            value: $(this)[0].value,
            type: 'getLists',
        },
        success: function (data) {
            data = JSON.parse(data);
            data.forEach(element => {
                element.pids = JSON.parse(element.pids);
            });
            console.log('data', data);
            
            family.load(data);
        }
        // beforeSend: function( xhr ) {
        //     xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
        // }
    }).done(function (data) {
    });
});

async function add(nodeId, type) {
    if (type == 'addFather') {
        
        family.addParentNode(nodeId, "fid", {
            id: getRndInteger(10, 9999),
            name: "father",
            gender: "male"
        });
    }
    if (type == 'addMother') {
        family.addParentNode(nodeId, "mid", {
            id: getRndInteger(10, 9999),
            name: "mother",
            gender: "female"
        });
    }
    if (type == 'addHusband') {
        
        let data = {
            id: nodeId,
            name: 'Doan Van A',
            couple_id: '['+nodeId+']',
            gender: 'male',
            type: 'addHusband',
        }
        await callApi(data).done(function( data ) {
            data = JSON.parse(data);
            data = data.data;
            data.pids = JSON.parse(data.pids);
            family.addPartnerNode({
                id: data.id,
                pids: data.pids,
                name: data.name,
                gender: data.gender
            });
        });
    }
    if (type == 'addWife') {
        let data = {
            id: nodeId,
            name: 'Doan Thi B',
            couple_id: '['+nodeId+']',
            gender: 'female',
            type: 'addWife',
        }
        await callApi(data).done(function( data ) {
            data = JSON.parse(data);
            data = data.data;
            data.pids = JSON.parse(data.pids);
            family.addPartnerNode({
                id: data.id,
                pids: data.pids,
                name: data.name,
                gender: data.gender
            });
        });
    }
    if (type == 'addChildMale') {
        let node = family.get(nodeId);
        let is_father = true;
        if (node.gender == "female") {
            console.log('female nè');
            
            is_father = false;
        }
        let data = {
            name: 'Doan Van A',
            parent_id: nodeId,
            is_father: is_father,
            gender: 'male',
            type: 'addChild',
        }
        await callApi(data).done(function( data ) {
            data = JSON.parse(data);
            data = data.data;
            family.addChildNode({
                id: data.id,
                mid: data.mid,
                fid: data.fid,
                name: data.name,
                gender: data.gender
            });
        });

    }
    if (type == 'addChildFemale') {
        let node = family.get(nodeId);
        let is_father = true;
        if (node.gender == "female") {
            is_father = false;
        }
        let data = {
            name: 'Doan Thi B',
            parent_id: nodeId,
            is_father: is_father,
            gender: 'female',
            type: 'addChild',
        }
        await callApi(data).done(function( data ) {
            data = JSON.parse(data);
            data = data.data;
            family.addChildNode({
                id: data.id,
                mid: data.mid,
                fid: data.fid,
                name: data.name,
                gender: data.gender
            });
        });
    }
}

function callApi(data)
{
    return $.ajax({
        url: "/backend/api.php",
        method:'POST',
        data: data,
        success: function (data) {
        }
        // beforeSend: function( xhr ) {
        //     xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
        // }
    })
}