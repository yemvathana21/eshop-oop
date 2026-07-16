<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">My Orders</h1>

    <?php if (empty($orders)): ?>
    <div class="text-center py-20 bg-white rounded-xl shadow-sm border border-gray-100">
        <i class="fas fa-receipt text-6xl text-gray-300 mb-4"></i>
        <p class="text-gray-500 text-lg mb-4">You haven't placed any orders yet.</p>
        <a href="<?= BASE_URL ?>" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium transition">
            <i class="fas fa-shopping-bag mr-2"></i>Start Shopping
        </a>
    </div>
    <?php else: ?>
    <div class="space-y-4">
        <?php foreach ($orders as $order): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100">
                <div>
                    <div class="flex items-center gap-3">
                        <span class="font-bold text-gray-900">#<?= htmlspecialchars($order['invoice_number']) ?></span>
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold
                            <?= $order['status'] === 'completed' ? 'bg-green-100 text-green-700' : ($order['status'] === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') ?>">
                            <?= ucfirst($order['status']) ?>
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mt-1"><?= date('M d, Y \a\t g:i A', strtotime($order['created_at'])) ?></p>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-xl font-bold text-blue-600">$<?= number_format($order['total_price'], 2) ?></span>
                    <span class="text-sm text-gray-400"><?= count($order['items']) ?> item(s)</span>
                </div>
            </div>
            <div class="p-5">
                <div class="flex flex-wrap gap-3 mb-4">
                    <?php foreach ($order['items'] as $item): ?>
                    <div class="flex items-center gap-2 bg-gray-50 rounded-lg px-3 py-2">
                        <div class="w-8 h-8 bg-gray-200 rounded-md flex-shrink-0 overflow-hidden flex items-center justify-center">
                            <?php if ($item['product_image'] && file_exists(UPLOAD_PATH . $item['product_image'])): ?>
                                <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($item['product_image']) ?>" class="w-full h-full object-cover" alt="">
                            <?php else: ?>
                                <i class="fas fa-image text-gray-300 text-xs"></i>
                            <?php endif; ?>
                        </div>
                        <span class="text-sm text-gray-700 truncate max-w-[160px]" title="<?= htmlspecialchars($item['product_name']) ?>"><?= htmlspecialchars($item['product_name']) ?> x<?= $item['quantity'] ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="flex gap-3">
                    <a href="<?= BASE_URL ?>invoice?inv=<?= htmlspecialchars($order['invoice_number']) ?>" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                        <i class="fas fa-eye mr-2"></i>View Invoice
                    </a>
                    <button onclick="downloadOrderPDF(this)" 
                        data-inv="<?= htmlspecialchars($order['invoice_number']) ?>"
                        data-date="<?= date('M d, Y', strtotime($order['created_at'])) ?>"
                        data-name="<?= htmlspecialchars(App\Core\Session::get('user_name', '')) ?>"
                        data-email="<?= htmlspecialchars(App\Core\Session::get('user_email', '')) ?>"
                        data-total="<?= number_format($order['total_price'], 2, '.', '') ?>"
                        data-items='<?= json_encode(array_map(fn($i) => ["name" => $i["product_name"], "qty" => (int)$i["quantity"], "price" => number_format($i["price"], 2, '.', ''), "total" => number_format($i["price"] * $i["quantity"], 2, '.', '')], $order["items"])) ?>'
                        class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                        <i class="fas fa-download mr-2"></i>Download PDF
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
<script>
function downloadOrderPDF(btn) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('p', 'mm', 'a4');
    const pageWidth = doc.internal.pageSize.getWidth();

    const invoiceNumber = btn.dataset.inv;
    const orderDate = btn.dataset.date;
    const customerName = btn.dataset.name;
    const customerEmail = btn.dataset.email;
    const totalPrice = btn.dataset.total;
    const items = JSON.parse(btn.dataset.items);

    doc.setFillColor(245, 245, 245);
    doc.rect(0, 0, pageWidth, 297, 'F');
    doc.setFillColor(255, 255, 255);
    doc.roundedRect(15, 15, pageWidth - 30, 267, 3, 3, 'F');
    doc.setFillColor(37, 99, 235);
    doc.rect(15, 15, pageWidth - 30, 30, 'F');

    doc.setTextColor(255, 255, 255);
    doc.setFontSize(22);
    doc.setFont('helvetica', 'bold');
    doc.text('E-Shop', 25, 34);
    doc.setFontSize(10);
    doc.setFont('helvetica', 'normal');
    doc.text('INVOICE', pageWidth - 25, 28, { align: 'right' });
    doc.text(invoiceNumber, pageWidth - 25, 34, { align: 'right' });

    let y = 58;
    doc.setTextColor(100, 100, 100);
    doc.setFontSize(9);
    doc.setFont('helvetica', 'bold');
    doc.text('BILL TO', 25, y);
    doc.text('INVOICE DETAILS', pageWidth - 25, y, { align: 'right' });

    y += 6;
    doc.setTextColor(40, 40, 40);
    doc.setFontSize(11);
    doc.setFont('helvetica', 'bold');
    doc.text(customerName, 25, y);
    doc.setFontSize(10);
    doc.setFont('helvetica', 'normal');
    doc.text('Date: ' + orderDate, pageWidth - 25, y, { align: 'right' });

    y += 6;
    doc.setTextColor(100, 100, 100);
    doc.setFontSize(9);
    doc.text(customerEmail, 25, y);
    doc.text('Invoice: ' + invoiceNumber, pageWidth - 25, y, { align: 'right' });

    y += 10;
    doc.setDrawColor(220, 220, 220);
    doc.line(25, y, pageWidth - 25, y);

    y += 5;
    const tableData = items.map(item => [item.name, item.qty.toString(), '$' + item.price, '$' + item.total]);

    doc.autoTable({
        startY: y,
        margin: { left: 25, right: 25 },
        head: [['Item', 'Qty', 'Unit Price', 'Total']],
        body: tableData,
        theme: 'plain',
        styles: { fontSize: 10, cellPadding: 5, textColor: [40,40,40], lineColor: [230,230,230], lineWidth: 0.1 },
        headStyles: { fillColor: [245,245,245], textColor: [80,80,80], fontStyle: 'bold', fontSize: 9 },
        alternateRowStyles: { fillColor: [252,252,252] },
        columnStyles: { 0: { cellWidth: 'auto', fontStyle: 'bold' }, 1: { halign: 'center', cellWidth: 25 }, 2: { halign: 'right', cellWidth: 35 }, 3: { halign: 'right', cellWidth: 35, fontStyle: 'bold' } }
    });

    y = doc.lastAutoTable.finalY + 8;
    doc.setFillColor(239, 246, 255);
    doc.roundedRect(25, y, pageWidth - 50, 16, 2, 2, 'F');
    doc.setFontSize(12);
    doc.setFont('helvetica', 'bold');
    doc.setTextColor(40, 40, 40);
    doc.text('TOTAL', 35, y + 11);
    doc.setTextColor(37, 99, 235);
    doc.setFontSize(16);
    doc.text('$' + totalPrice, pageWidth - 35, y + 11, { align: 'right' });

    doc.setTextColor(160, 160, 160);
    doc.setFontSize(8);
    doc.setFont('helvetica', 'normal');
    doc.text('Thank you for shopping with us!', pageWidth / 2, 272, { align: 'center' });
    doc.text('E-Shop - Your trusted online store', pageWidth / 2, 277, { align: 'center' });

    doc.save('Invoice-' + invoiceNumber + '.pdf');
}
</script>
