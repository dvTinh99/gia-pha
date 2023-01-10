
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

    this.saveButton.addEventListener("click", async function () {
        console.log('save nè');

        var node = family.get(that.nodeId);
        node.name = that.nameInput.value;
        node.id = that.idInput.value;
        node.fid = that.idFather.value;
        node.mid = that.idMother.value;
        
        if (that.idCouple.value != '') {
            node.pids = [that.idCouple.value];
        } else {
            node.pids = [];
        }
        let data = {
            node : node,
            type : 'update'
        }

        await callApi(data).done(data => {
            data = JSON.parse(data);
            data = data.data;
            data.pids = JSON.parse(data.pids);
            
            family.updateNode(data);
            that.hide();
        });

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
            text: "+ Bố", onClick: function (nodeID) {
                console.log('nodeid', nodeID);
                add(nodeID, 'addFather');
            }
        },
        addMother: {
            text: "+ Mẹ", onClick: function (nodeID) {
                console.log('nodeid', nodeID);
                add(nodeID, 'addMother');
            }
        },
        addHusband: {
            text: "+ Chồng", onClick: function (nodeID) {
                console.log('nodeid', nodeID);
                add(nodeID, 'addHusband');
            }
        },
        addWife: {
            text: "+ Vợ", onClick: function (nodeID) {
                console.log('nodeid', nodeID);
                add(nodeID, 'addWife');
            }
        },
        addChildMale: {
            text: "+ Con trai", onClick: function (nodeID) {
                console.log('nodeid', nodeID);
                add(nodeID, 'addChildMale');
            }
        },
        addChildFemale: {
            text: "+ Con gái", onClick: function (nodeID) {
                console.log('nodeid', nodeID);
                add(nodeID, 'addChildFemale');
            }
        },
        delete: {
            text: "Xoá", onClick: function (nodeID) {
                console.log('nodeid', nodeID);
                add(nodeID, 'delete');
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
        let data = {
            id : nodeId,
            type: 'addFather'
        }
        await callApi(data).done(function( data ) {
            data = JSON.parse(data);
            data = data.data;
            data.pids = JSON.parse(data.pids);
            family.addParentNode(nodeId, 'fid', data);
            // family.addPartnerNode(data);
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
            family.addPartnerNode(data);
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
            family.addPartnerNode(data);
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
            family.addChildNode(data);
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
            family.addChildNode(data);
        });
    }
    if (type == 'delete') {
        let data = {
            id: nodeId,
            type: 'delete',
        }
        await callApi(data).done(function( data ) {
            family.removeNode(nodeId);
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