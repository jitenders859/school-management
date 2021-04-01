<div class="w3-row">
    <div class="w3-container">   
        <h1><?= $headline ?> <span class="w3-medium">(ID: <?= $update_id ?>)</span></h1>
        <?= flashdata() ?>
        <div class="w3-card-4">
            <div class="w3-container primary">
                <h4>Options</h4>
            </div>

            <div class="w3-container">
            <p>
                <a href="<?= BASE_URL ?>subject_types/manage"><button class="w3-button w3-white w3-border"><i class="fa fa-list-alt"></i> VIEW ALL SUBJECT TYPES</button></a> 
                <a href="<?= BASE_URL ?>subject_types/create/<?= $update_id ?>"><button class="w3-button w3-white w3-border"><i class="fa fa-pencil"></i> UPDATE DETAILS</button></a>
                <button onclick="document.getElementById('delete-record-modal').style.display='block'" class="w3-button w3-red w3-hover-black w3-border w3-right"><i class="fa fa-trash-o"></i> DELETE</button>

                <div id="delete-record-modal" class="w3-modal w3-center" style="padding-top: 7em;">
                    <div class="w3-modal-content w3-animate-right w3-card-4" style="width: 30%;">
                        <header class="w3-container w3-red w3-text-white">
                            <h4><i class="fa fa-trash-o"></i> DELETE RECORD</h4>
                        </header>
                        <div class="w3-container">
                            <?php 
                            echo form_open('subject_types/submit_delete/'.$update_id);
                            ?>
                            <h5>Are you sure?</h5>
                            <p>You are about to delete a subject type record.  This cannot be undone. <br>
                                        Do you really want to do this?</p>
                            <p class="w3-right modal-btns">
                                <button onclick="document.getElementById('delete-record-modal').style.display='none'" type="button" name="submit" value="Submit" class="w3-button w3-small 3-white w3-border">CANCEL</button> 
                                <button type="submit" name="submit" value="Submit" class="w3-button w3-small w3-red w3-hover-black">YES - DELETE IT NOW!</button> 
                            </p>
                            <?= form_close() ?>
                        </div>
                    </div>
                </div>
            </p>        
            </div>
        </div>
    </div>
</div>

<div class="w3-row">
<div class="w3-third w3-container">    
        <div class="w3-card-4 edit-block" style="margin-top: 1em;">
            <div class="w3-container primary">
                <h4>Subject Type Details</h4>
            </div>
            <div class="edit-block-content">
              <div class="w3-border-bottom"><b>Title:</b> <span class="w3-right w3-text-grey"><?= $title ?></span></div>              
            </div>
        </div>
    </div>
    
<?= Modules::run('associated_subjects_and_subject_types/_draw_association_info', $token) ?>

<div class="w3-third w3-container">    
        <div class="w3-card-4 edit-block" style="margin-top: 1em;">
            <div class="w3-container primary">
                <h4>Comments</h4>
            </div>
            <div class="w3-container w3-center edit-block-content">
                <?php
                echo Modules::run('comments/_display_comments_block', $token);
                ?>
            </div>
        </div>
    </div>
</div>
<script>

function extractRelationshipModule(apiUrl, relationshipModuleSegment) {
    apiUrl = apiUrl.replace('<?= BASE_URL ?>', '');
    var targetSegment = relationshipModuleSegment-1;
    var segments = apiUrl.split("/");
    var relationshipModule = segments[targetSegment];

    return relationshipModule;
}

function sendToApi(apiUrl, requestType, params) {

    const http = new XMLHttpRequest()
    http.open(requestType, apiUrl)
    http.setRequestHeader('Content-type', 'application/json')
    http.setRequestHeader("trongateToken", '<?= $token ?>')
    http.send(JSON.stringify(params))
    http.onload = function() {

        if (http.status == 200) {

            switch(params.thenRun) {
                case 'populateResultsArea':
                    var relationshipModule = extractRelationshipModule(apiUrl, 1);
                    populateResultsArea(http.responseText, relationshipModule);   
                    break;
                case 'populateDropdown':
                    var relationshipModule = extractRelationshipModule(apiUrl, 1);
                    populateDropdown(http.responseText, relationshipModule);
                    break;
                case 'repopulate':
                    var relationshipModule = extractRelationshipModule(apiUrl, 3);
                    populateAssociationInfo(relationshipModule);
                    break;
            }

        } else {

            if (http.responseText == 'Invalid token.') {
                alert("invalid token");
                window.location.href = "<?= current_url() ?>";
            } else {
                var elementId = params.relationshipModule + '-summary';
                var errorMsg = '<h5>API Error</h5>';
                errorMsg+= '<p>The following error message was received from the API:</p>' + '<p>' + http.responseText + '</p>';
                document.getElementById(elementId).innerHTML = errorMsg;
            }

        }

    }
}

function populateAssociationInfo(relationshipModule) {

    var apiUrl = '<?= BASE_URL ?>' + relationshipModule + '/fetch/<?= $update_id ?>/subject_types';
    var params = {
        thenRun: 'populateResultsArea'
    }

    sendToApi(apiUrl, 'GET', params)
}

function populateResultsArea(responseText, relationshipModule) {

    var results = JSON.parse(responseText);
    var elementId = relationshipModule + '-summary';

    if (results.length>0) {
        
        
        var deleteAssocBtnHtml = '<button onclick="confDisassociate(\'xxxx\', \'' + relationshipModule + '\')" class="w3-button w3-red w3-hover-black w3-border w3-small"><i class="fa fa-ban"></i> Disassociate</button>';

        var resultsHtml = '<div class="results-list">';

        for (var i = 0; i < results.length; i++) {

            var recordId = results[i]['recordId'];
            var identifierColumn = results[i]['identifierColumn'];

            deleteAssocBtnHtml = deleteAssocBtnHtml.replace('xxxx', recordId);

            resultsHtml = resultsHtml.concat('<div class="results-row"><div>' + identifierColumn + '</div><div>' +  deleteAssocBtnHtml + '</div></div>');

            deleteAssocBtnHtml = deleteAssocBtnHtml.replace(recordId, 'xxxx');

        }

        resultsHtml = resultsHtml.concat('</div>');
        document.getElementById(elementId).innerHTML = resultsHtml;

    } else {
        document.getElementById(elementId).innerHTML = '<p>No associated records exist</p>';
    }

    fetchAvailableRecords(results, relationshipModule); //for the dropdown
}

function populateDropdown(responseText, relationshipModule) {

    var dropdownId = relationshipModule + '-dropdown';
    var dropdownContainerId = relationshipModule + '-create';
    var results = JSON.parse(responseText);

    if (results.length<1) {
        document.getElementById(dropdownContainerId).style.display = 'none';
    } else {

        var dropdownCode = '';

        for (var i = 0; i < results.length; i++) {
            var thisRow = '<option value=\'' + results[i]['id'] + '\'>' + results[i]['identifier_column'] + '</option>';
            dropdownCode = dropdownCode.concat(thisRow);
        }                    

        document.getElementById(dropdownContainerId).style.display = 'block';
        document.getElementById(dropdownId).innerHTML = dropdownCode;

    }   

}

function fetchAvailableRecords(results, relationshipModule) {

    var selectedRecords = [];
    for (var i = 0; i < results.length; i++) {
        selectedRecords.push(results[i]['id']);
    }

    var apiUrl = '<?= BASE_URL ?>' + relationshipModule + '/get_dropdown_options';

    var params = {
        callingModuleName: 'subject_types',
        updateId: <?= $update_id ?>,
        selectedRecords,
        thenRun: 'populateDropdown'
    }
    
    sendToApi(apiUrl, 'POST', params);
}

function submitAssoc(relationshipModule) {
    //create a new relationship
    var elementId = relationshipModule + '-modal';
    var dropdownId = relationshipModule + '-dropdown';
    var selectedValue = document.getElementById(dropdownId).value;
    var apiUrl = '<?= BASE_URL ?>api/create/' + relationshipModule;

    var params =  {
        update_id: <?= $update_id ?>,
        selectedValue,
        requestCode: "SAS",
        relationshipModule,
        callingModule: 'subject_types',
        thenRun: 'repopulate'
    }

    sendToApi(apiUrl, 'POST', params);
    closeModal(elementId);
}

function confDisassociate(recordId, relationshipModule) {
    var modalId = relationshipModule + '-disassociate-modal';
    var btnId = relationshipModule + '-disassociateBtn';
    document.getElementById(modalId).style.display = 'block';
    document.getElementById(btnId).value = recordId;
}

function disassociate(relationshipModule) {

    var btnId = relationshipModule + '-disassociateBtn';
    var recordId = document.getElementById(btnId).value;    
    var modalId = relationshipModule + '-disassociate-modal';
    
    var apiUrl = '<?= BASE_URL ?>api/delete/' + relationshipModule + '/' + recordId;

    document.getElementById(modalId).style.display = 'none';

    var params = {
        thenRun: 'repopulate'
    }
    closeModal(modalId);  
    sendToApi(apiUrl, 'DELETE', params);

}

function openModal(elementId) {
    document.getElementById(elementId).style.display = 'block';
}

function closeModal(elementId) {
    document.getElementById(elementId).style.display = 'none';
}

window.onload = function() {
    //attempt to populate relationship panels on the page
    populateAssociationInfo('associated_subjects_and_subject_types');

}
</script>
