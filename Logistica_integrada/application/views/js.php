<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script>

$(function(){
	

	
	var nCloneTh = document.createElement( 'th' );
				var nCloneTd = document.createElement( 'td' );
				nCloneTd.innerHTML = '<img src="<?php echo base_url();?>js/datatable/examples/examples_support/details_open.png">';
				nCloneTd.className = "center";
				
				$('#example thead tr').each( function () {
					this.insertBefore( nCloneTh, this.childNodes[0] );
				} );
				
				$('#example tbody tr').each( function () {
					this.insertBefore(  nCloneTd.cloneNode( true ), this.childNodes[0] );
				} );
				
				/*
				 * Initialse DataTables, with no sorting on the 'details' column
				 */
				var oTable = $('#example').dataTable( {
					"bJQueryUI": true,
					"sPaginationType": "full_numbers",
					"aLengthMenu": [[15], [15]],
					"iDisplayLength": 15,
					//"bProcessing": true,
                    //"bServerSide": true,
                    //"sAjaxSource": 'AjaxHandler',
					"aoColumnDefs": [
						{ "bSortable": false, "aTargets": [ 0 ] }
					],
					"aaSorting": [[1, 'asc']]
				});
				
				/* Add event listener for opening and closing details
				 * Note that the indicator for showing which row is open is not controlled by DataTables,
				 * rather it is done here
				 */
				$('#example tbody td img').live('click', function () {
					var nTr = this.parentNode.parentNode;
					if ( this.src.match('details_close') )
					{
						/* This row is already open - close it */
						this.src = "<?php echo base_url();?>js/datatable/examples/examples_support/details_open.png";
						oTable.fnClose( nTr );
					}
					else
					{
						/* Open this row */
						//var aData = oTable.fnGetData( nTr );
						//alert(aData[1]);
						this.src = "<?php echo base_url();?>js/datatable/examples/examples_support/details_close.png";
						oTable.fnOpen( nTr, fnFormatDetails(oTable, nTr), 'details' );
					}
				} );
});


</script>
</head>

<body>
</body>
</html>