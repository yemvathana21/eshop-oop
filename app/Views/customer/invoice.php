<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-3">
            <i class="fas fa-check text-3xl text-green-600"></i>
        </div>
        <h1 class="text-3xl font-bold text-gray-900">Invoice</h1>
        <p class="text-gray-500 mt-1"><?= htmlspecialchars($order['invoice_number']) ?></p>
    </div>

    <div id="invoiceContent" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gray-50 border-b border-gray-100 p-6">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center space-x-2 mb-2">
                        <div class="bg-blue-600 text-white w-8 h-8 rounded-lg flex items-center justify-center font-bold">E</div>
                        <span class="text-xl font-bold text-gray-800">E-Shop</span>
                    </div>
                    <p class="text-gray-500 text-sm">Your trusted online store</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Invoice Number</p>
                    <p class="font-bold text-gray-900"><?= htmlspecialchars($order['invoice_number']) ?></p>
                    <p class="text-sm text-gray-500 mt-1"><?= date('M d, Y', strtotime($order['created_at'])) ?></p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Bill To</p>
                    <p class="font-bold text-gray-900"><?= htmlspecialchars($order['user_name']) ?></p>
                    <p class="text-gray-600 text-sm"><?= htmlspecialchars($order['user_email']) ?></p>
                </div>
                <?php if (!empty($order['shipping_name'])): ?>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Ship To</p>
                    <p class="font-bold text-gray-900"><?= htmlspecialchars($order['shipping_name']) ?></p>
                    <p class="text-gray-600 text-sm"><?= htmlspecialchars($order['shipping_address']) ?></p>
                    <?php if (!empty($order['shipping_method'])): ?>
                    <p class="text-gray-500 text-xs mt-1">via <?= htmlspecialchars($order['shipping_method']) ?></p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                <?php if (!empty($order['payment_method'])): ?>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Payment</p>
                    <p class="font-bold text-gray-900"><?= $order['payment_method'] === 'cod' ? 'Cash on Delivery' : 'Credit / Debit Card' ?></p>
                </div>
                <?php endif; ?>
            </div>

            <table class="w-full text-sm mb-6">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left py-3 px-4 font-semibold text-gray-600 rounded-l-lg">Item</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-600">Qty</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-600">Price</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-600 rounded-r-lg">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-100 rounded flex-shrink-0 overflow-hidden flex items-center justify-center">
                                    <?php if (!empty($item['product_image'])): ?>
                                    <img src="<?= BASE_URL ?>uploads/<?= rawurlencode($item['product_image']) ?>"
                                         onerror="this.src='<?= BASE_URL ?>images/<?= rawurlencode($item['product_image']) ?>'; this.onerror=null;"
                                         class="w-full h-full object-cover" alt="">
                                    <?php else: ?>
                                    <i class="fas fa-image text-gray-300"></i>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-900"><?= htmlspecialchars($item['product_name']) ?></span>
                                    <?php if (!empty($item['color_name']) || !empty($item['size_name'])): ?>
                                    <p class="text-[10px] text-gray-400 mt-0.5">
                                        <?= !empty($item['color_name']) ? htmlspecialchars($item['color_name']) : '' ?>
                                        <?= !empty($item['color_name']) && !empty($item['size_name']) ? ' / ' : '' ?>
                                        <?= !empty($item['size_name']) ? htmlspecialchars($item['size_name']) : '' ?>
                                    </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-center text-gray-600"><?= $item['quantity'] ?></td>
                        <td class="py-3 px-4 text-right text-gray-600">$<?= number_format($item['price'], 2) ?></td>
                        <td class="py-3 px-4 text-right font-semibold text-gray-900">$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="bg-blue-50 rounded-xl p-4 flex justify-between items-center">
                <span class="text-lg font-bold text-gray-900">Total Amount</span>
                <span class="text-2xl font-bold text-blue-600">$<?= number_format($order['total_price'], 2) ?></span>
            </div>
        </div>

        <div class="bg-gray-50 border-t border-gray-100 p-6 text-center">
            <p class="text-xs text-gray-400">Thank you for shopping with us!</p>
        </div>
    </div>

    <div class="text-center mt-6 space-x-4">
        <button onclick="downloadPDF()" class="inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg font-medium transition text-sm">
            <i class="fas fa-download mr-2"></i>Download PDF
        </button>
        <button onclick="window.print()" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium transition text-sm">
            <i class="fas fa-print mr-2"></i>Print
        </button>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
<script>
function downloadPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('p', 'mm', 'a4');
    const pageWidth = doc.internal.pageSize.getWidth();

    const invoiceNumber = <?= json_encode($order['invoice_number']) ?>;
    const orderDate = <?= json_encode(date('M d, Y', strtotime($order['created_at']))) ?>;
    const customerName = <?= json_encode($order['user_name']) ?>;
    const customerEmail = <?= json_encode($order['user_email']) ?>;
    const totalPrice = <?= json_encode(number_format($order['total_price'], 2, '.', '')) ?>;
    const items = <?= json_encode(array_map(fn($i) => [
        'name' => $i['product_name'] . (!empty($i['color_name']) || !empty($i['size_name']) ? ' (' . trim(($i['color_name'] ?? '') . ' ' . ($i['size_name'] ?? '')) . ')' : ''),
        'qty' => (int)$i['quantity'],
        'price' => number_format($i['price'], 2, '.', ''),
        'total' => number_format($i['price'] * $i['quantity'], 2, '.', '')
    ], $items)) ?>;

    // Background
    doc.setFillColor(245, 245, 245);
    doc.rect(0, 0, pageWidth, 297, 'F');

    // White paper
    doc.setFillColor(255, 255, 255);
    doc.roundedRect(15, 15, pageWidth - 30, 267, 3, 3, 'F');

    // Blue header bar
    doc.setFillColor(37, 99, 235);
    doc.rect(15, 15, pageWidth - 30, 30, 'F');

    // Company name
    doc.setTextColor(255, 255, 255);
    doc.setFontSize(22);
    doc.setFont('helvetica', 'bold');
    doc.text('E-Shop', 25, 34);

    doc.setFontSize(10);
    doc.setFont('helvetica', 'normal');
    doc.text('INVOICE', pageWidth - 25, 28, { align: 'right' });
    doc.text(invoiceNumber, pageWidth - 25, 34, { align: 'right' });

    // Invoice details section
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

    // Divider
    y += 10;
    doc.setDrawColor(220, 220, 220);
    doc.line(25, y, pageWidth - 25, y);

    // Table
    y += 5;
    const tableData = items.map(item => [
        item.name,
        item.qty.toString(),
        '$' + item.price,
        '$' + item.total
    ]);

    doc.autoTable({
        startY: y,
        margin: { left: 25, right: 25 },
        head: [['Item', 'Qty', 'Unit Price', 'Total']],
        body: tableData,
        theme: 'plain',
        styles: {
            fontSize: 10,
            cellPadding: 5,
            textColor: [40, 40, 40],
            lineColor: [230, 230, 230],
            lineWidth: 0.1
        },
        headStyles: {
            fillColor: [245, 245, 245],
            textColor: [80, 80, 80],
            fontStyle: 'bold',
            fontSize: 9
        },
        alternateRowStyles: {
            fillColor: [252, 252, 252]
        },
        columnStyles: {
            0: { cellWidth: 'auto', fontStyle: 'bold' },
            1: { halign: 'center', cellWidth: 25 },
            2: { halign: 'right', cellWidth: 35 },
            3: { halign: 'right', cellWidth: 35, fontStyle: 'bold' }
        }
    });

    // Total
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

    // Footer
    doc.setTextColor(160, 160, 160);
    doc.setFontSize(8);
    doc.setFont('helvetica', 'normal');
    doc.text('Thank you for shopping with us!', pageWidth / 2, 272, { align: 'center' });
    doc.text('E-Shop - Your trusted online store', pageWidth / 2, 277, { align: 'center' });

    // Save
    doc.save('Invoice-' + invoiceNumber + '.pdf');
}
</script>
