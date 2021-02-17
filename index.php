<?php 
//to create or update the json file 
if(isset($_POST['json'])){
	$decoded = base64_decode($_POST['json']);
	$jsonFile = fopen('matricsheet.json','w+');
	fwrite($jsonFile,$decoded);
	fclose($jsonFile);
}?>

<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.5/xlsx.full.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.5/jszip.js"></script>
		<style>
			input[type="file"] {
				display: none;
			}
			.custom-file-upload {
				border: 1px solid #ccc;
				display: inline-block;
				padding: 6px 12px;
				cursor: pointer;
				font-size: 14px;
				line-height: 1.42857143;
				border-radius: 4px;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<form action=""  style="text-align:center;margin: 6% 0 0 0;">
						<div class="form-group">
							<label class="custom-file-upload">
								<input type="file" id="fileUpload" accept=".xlsx"/>
								<i class="fa fa-cloud-upload" aria-hidden="true"></i> Upload .xlsx file
							</label>
							
						</div>
						<input type="button" id="upload" value="Convert" class="btn btn-primary" onclick="Upload()" />
					</form>
					<hr />
					<div id="dvExcel"></div>
					<!--Script starts here-->
					<script type="text/javascript">
						function Upload() {
							//Reference the FileUpload element.
							var fileUpload = document.getElementById("fileUpload");
					 
							//Validate whether File is valid Excel file.
							var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
							if (regex.test(fileUpload.value.toLowerCase())) {
								if (typeof (FileReader) != "undefined") {
									var reader = new FileReader();
					 
									//For Browsers other than IE.
									if (reader.readAsBinaryString) {
										reader.onload = function (e) {
											ProcessExcel(e.target.result);
										};
										reader.readAsBinaryString(fileUpload.files[0]);
									} else {
										//For IE Browser.
										reader.onload = function (e) {
											var data = "";
											var bytes = new Uint8Array(e.target.result);
											for (var i = 0; i < bytes.byteLength; i++) {
												data += String.fromCharCode(bytes[i]);
											}
											ProcessExcel(data);
										};
										reader.readAsArrayBuffer(fileUpload.files[0]);
									}
								} else {
									alert("This browser does not support HTML5.");
								}
							} else {
								alert("Please upload a valid Excel file.");
							}
						};
						function ProcessExcel(data) {
							//Read the Excel File data.
							var workbook = XLSX.read(data, {
								type: 'binary'
							});
					 
							//Fetch the name of First Sheet.
							var firstSheet = workbook.SheetNames[0];
					 
							//Read all rows from First Sheet into an JSON array.
							var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
					 
							
							var array = []
							//Add the data rows from Excel file.
							for (var i = 0; i < excelRows.length; i++) {
							   
								//Add the data cells.
								var cell = {
									"LENGTH": excelRows[i].LENGTH,
									"WIDTH": excelRows[i].WIDTH,
									"OUTPUT": excelRows[i].OUTPUT
								}
								
								array.push(cell);
							}
							var myJSON = JSON.stringify(array);
							//to post json data to create file
							var encoded = btoa(myJSON);
							var xhr = new XMLHttpRequest();
							xhr.open('POST','index.php',true);
							xhr.setRequestHeader('Content-type','application/x-www-form-urlencoded');
							xhr.send('json=' + encoded);
							document.getElementById("dvExcel").innerHTML = '<div class="alert alert-success"><strong>Json</strong> file created successfully.</div>';
						};
					</script>
					<!--Script ends here-->
				</div>
			</div>
		</div>
	</body>
</html>
