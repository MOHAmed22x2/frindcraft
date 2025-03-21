<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];  // البريد الإلكتروني الذي سيتم إرسال الفاتورة إليه
    $type = $_POST['type'];    // نوع الفاتورة (فودافون أو بنك)
    $file = $_FILES['receipt'];

    // تحديد مجلد حفظ الفاتورة
    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . basename($file['name']);

    // رفع الفاتورة إلى الخادم
    if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
        // إرسال بريد إلكتروني
        $to = 'farsfarsmado@gmail.com';
        $subject = 'تم رفع فاتورة جديدة';
        $message = 'تم رفع فاتورة جديدة من نوع: ' . $type . "\n" . 'يمكنك الاطلاع على الفاتورة المرفقة.';
        $headers = 'From: no-reply@yourdomain.com' . "\r\n" .
                   'Content-Type: text/plain; charset=UTF-8';

        // إضافة الفاتورة كمرفق للبريد
        $fileContent = file_get_contents($uploadFile);
        $fileEncoded = base64_encode($fileContent);
        $headers .= "\r\n" . 'Content-Disposition: attachment; filename="' . basename($file['name']) . '"';
        $headers .= "\r\n" . 'Content-Transfer-Encoding: base64';
        $headers .= "\r\n" . 'Content-Type: application/octet-stream; name="' . basename($file['name']) . '"';
        
        mail($to, $subject, $message, $headers);
        
        echo 'تم رفع الفاتورة بنجاح.';
    } else {
        echo 'فشل في رفع الفاتورة.';
    }
}
?>
