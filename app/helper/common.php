<?php

function formatWhatsAppNumberInto62Format($whatsAppNumber) {
    $whatsAppNumber = preg_replace('/[^\d]/', '', $whatsAppNumber);  // Remove non-digit characters
    
    // Phone number validation
    if (!preg_match('/^(\+62|62)?[\s-]?0?8[1-9]{1}\d{1}[\s-]?\d{4}[\s-]?\d{2,5}$/', $whatsAppNumber)) {
        return $whatsAppNumber; // return it as is
    }

    // Check inputted phoneNumber with "8" prefix
    if (strlen($whatsAppNumber) == 9 && $whatsAppNumber[0] == 8) {
        $whatsAppNumber = "62" . $whatsAppNumber;
    } else if (strlen($whatsAppNumber) == 10 && $whatsAppNumber[0] == 8) {
        $whatsAppNumber = "62" . $whatsAppNumber;
    } else if (strlen($whatsAppNumber) == 11 && $whatsAppNumber[0] == 8) {
        $whatsAppNumber = "62" . $whatsAppNumber;
    } else if (strlen($whatsAppNumber) == 12 && $whatsAppNumber[0] == 8) {
        $whatsAppNumber = "62" . $whatsAppNumber;
    }
    
    // Check inputted phoneNumber with "0" prefix
    if (strlen($whatsAppNumber) == 10 && $whatsAppNumber[0] == 0) {
        $whatsAppNumber = "62" . substr($whatsAppNumber, 1);
    } else if (strlen($whatsAppNumber) == 11 && $whatsAppNumber[0] == 0) {
        $whatsAppNumber = "62" . substr($whatsAppNumber, 1);
    } else if (strlen($whatsAppNumber) >= 12 && $whatsAppNumber[0] == 0) {
        $whatsAppNumber = "62" . substr($whatsAppNumber, 1);
    }

    return $whatsAppNumber;
}
