<?php

use SLiMS\Filesystems\Storage;

// create datagrid
$datagrid = new simbio_datagrid();

// table spec
$table_spec = 'room_reservations';

// set column
$datagrid->setSQLColumn('reservation_id AS \''.__('ID').'\'',
                        'reservation_date AS \''.__('Tanggal Reservasi').'\'', 
                        'name AS \''.__('Nama').'\'', 
                        'reservation_document_id AS \''.__('Dokumen').'\'', 
                        'member_id AS \''.__('NIM/NIDN').'\'', 
                        'major AS \''.__('Jurusan').'\'',
                        'whatsapp_number AS \''.__('No. WA').'\'',
                        'reserved_date AS \''.__('Tanggal').'\'',
                        'duration AS \''.__('Durasi').'\'',
                        'start_time AS \''.__('Awal').'\'',
                        'end_time AS \''.__('Akhir').'\'',
                        'visitor_number AS \'' . __('Jml. Agt.') . '\'',
                        'activity AS \'' . __('Kegiatan') . '\'',
                        'status AS \'' . __('Status') . '\'');

// ordering
$datagrid->setSQLorder('last_update DESC');
$datagrid->setSQLCriteria('status != \'ongoing\'');
$datagrid->modifyColumnContent(3, 'callback{createLinkableReservationDocument}'); // modify reservation_document_id content
$datagrid->modifyColumnContent(6, 'callback{createLinkableWhatsAppNumber}'); // modify whatsapp_number content

function createLinkableReservationDocument($obj_db, $row, $field_num) {
    $documentId = $row[$field_num];
    if ($documentId != 0) {
        $query = 'SELECT files.file_title
        FROM room_reservations
        JOIN files ON room_reservations.reservation_document_id = files.file_id
        WHERE room_reservations.reservation_document_id = ' . $row[$field_num];
        $result = $obj_db->query($query);

        $filesRow = $result->fetch_assoc();
        return '<a class="notAJAX openPopUp" style="color: blue; text-decoration: underline;" href="'.SWB.'admin/view.php?fid='.urlencode($row[$field_num]).'" width="780" height="500" target="_blank">'.$filesRow['file_title'].'</a>';
    } else {
        return '<a>Tidak ada surat</a>';
    }
}

function createLinkableWhatsAppNumber($obj_db, $row, $field_num) {
    return '<a href="https://wa.me/' . $row[$field_num] .'" target="_blank" style="color: blue; text-decoration: underline;">'.$row[$field_num].'</a>';
}

// set table and table header attributes
$datagrid->icon_edit = SWB.'admin/'.$sysconf['admin_template']['dir'].'/'.$sysconf['admin_template']['theme'].'/edit.gif';
$datagrid->table_name = 'reservationScheduleHistoryList';
$datagrid->table_attr = 'id="dataList" class="s-table table"';
$datagrid->table_header_attr = 'class="dataListHeader" style="font-weight: bold;"';
// set delete proccess URL
$datagrid->chbox_form_URL = null;

// put the result into variables
$datagrid_result = $datagrid->createDataGrid($dbs, $table_spec, 10, false);
if ((isset($_GET['keywords']) AND $_GET['keywords'])) {
    echo '<div class="infoBox">';
    echo __('Found').' '.$datagrid->num_rows.' '.__('from your search with keyword').' : "'.htmlentities($_GET['keywords']).'"'; //mfc
    echo '</div>';
}

echo $datagrid_result;
