<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    @media print {
        @page {
            size: 80mm auto;
            margin: 0;
        }
        body {
            background: white !important;
            margin: 0;
            padding: 0;
        }
        body * { visibility: hidden; }

        #receipt, #receipt * { visibility: visible; }
        #receipt {
            display: block !important;
            width: 80mm !important;
            padding: 10mm 5mm !important;
            margin: 0 !important;
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            box-shadow: none !important;
            border: none !important;
        }
        .no-print { display: none !important; }
    }

    #receipt {
        display: none;
        width: 85mm;
        margin: 20px auto;
        background: white;
        padding: 30px 20px;
        font-family: 'Inter', sans-serif;
        color: #1a1a1a;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
    }

    .receipt-header { text-align: center; margin-bottom: 20px; }
    .receipt-logo { font-size: 24px; font-weight: 800; letter-spacing: -0.025em; margin-bottom: 4px; color: #111; }
    .receipt-subtitle { font-size: 11px; color: #666; text-transform: uppercase; letter-spacing: 0.05em; }

    .receipt-info { font-size: 12px; margin-bottom: 20px; color: #444; line-height: 1.6; }
    .receipt-info p { display: flex; justify-content: space-between; }
    .receipt-info span:first-child { color: #888; }
    .receipt-info span:last-child { font-weight: 500; color: #000; }

    .receipt-divider { border-top: 1px dashed #e0e0e0; margin: 15px 0; }
    .receipt-divider-thick { border-top: 2px solid #000; margin: 15px 0; }

    .receipt-table { width: 100%; border-collapse: collapse; font-size: 12px; }
    .receipt-table th { text-align: left; font-weight: 600; color: #888; padding-bottom: 8px; text-transform: uppercase; font-size: 10px; }
    .receipt-table td { padding: 8px 0; vertical-align: top; }
    .receipt-item-name { font-weight: 600; color: #111; display: block; margin-bottom: 2px; }
    .receipt-item-meta { font-size: 10px; color: #666; }

    .receipt-totals { margin-top: 15px; }
    .receipt-total-row { display: flex; justify-content: space-between; padding: 4px 0; font-size: 13px; }
    .receipt-total-row.grand-total { font-size: 18px; font-weight: 800; margin-top: 10px; padding-top: 10px; border-top: 1px solid #000; }

    .receipt-footer { text-align: center; margin-top: 30px; font-size: 11px; color: #999; line-height: 1.5; }
</style>

<!-- Modern Thermal Receipt View -->
<div id="receipt">
    <div class="receipt-header">
        <div class="receipt-logo">GENERAL ONLINE STORE</div>
        <div class="receipt-subtitle">Thank you for your visit</div>
    </div>

    <div class="receipt-divider"></div>

    <div class="receipt-info">
        <p><span>Receipt #</span> <span><?= htmlspecialchars($order['invoice_number']) ?></span></p>
        <p><span>Date</span> <span><?= date('M d, Y H:i', strtotime($order['created_at'])) ?></span></p>
        <p><span>Customer</span> <span><?= htmlspecialchars($order['user_name']) ?></span></p>
        <?php if (!empty($order['shipping_phone'])): ?>
        <p><span>Phone</span> <span><?= htmlspecialchars($order['shipping_phone']) ?></span></p>
        <?php endif; ?>
    </div>

    <table class="receipt-table">
        <thead>
            <tr>
                <th style="width: 60%;">Description</th>
                <th style="width: 10%; text-align: center;">Qty</th>
                <th style="width: 30%; text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td>
                    <span class="receipt-item-name"><?= htmlspecialchars($item['product_name']) ?></span>
                    <?php if (!empty($item['color_name']) || !empty($item['size_name'])): ?>
                    <span class="receipt-item-meta">
                        <?= trim(($item['color_name'] ?? '') . ' ' . ($item['size_name'] ?? '')) ?>
                    </span>
                    <?php endif; ?>
                </td>
                <td style="text-align: center; color: #666;"><?= $item['quantity'] ?></td>
                <td style="text-align: right; font-weight: 500;">$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="receipt-divider"></div>

    <div class="receipt-totals">
        <div class="receipt-total-row">
            <span>Subtotal</span>
            <span>$<?= number_format($order['total_price'], 2) ?></span>
        </div>
        <div class="receipt-total-row grand-total">
            <span>TOTAL</span>
            <span>$<?= number_format($order['total_price'], 2) ?></span>
        </div>
    </div>

    <div class="receipt-footer">
        <p>Items once sold are non-refundable.</p>
        <p>www.generalonlinestore.com</p>
        <div style="margin-top: 10px; font-weight: 600; color: #000;">SEE YOU AGAIN!</div>
    </div>
</div>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 no-print">
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-3">
            <i class="fas fa-check text-3xl text-green-600"></i>
        </div>
        <h1 class="text-3xl font-bold text-gray-900">Receipt</h1>
        <p class="text-gray-500 mt-1">Receipt #<?= htmlspecialchars($order['invoice_number']) ?></p>
    </div>

    <div id="invoiceContent" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gray-50 border-b border-gray-100 p-6">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center space-x-2 mb-2">
                        <div class="bg-blue-600 text-white w-8 h-8 rounded-lg flex items-center justify-center font-bold">G</div>
                        <span class="text-xl font-bold text-gray-800">General Online Store</span>
                    </div>
                    <p class="text-gray-500 text-sm">Your trusted online store</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Receipt Number</p>
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
                    <?php if (!empty($order['shipping_phone'])): ?>
                    <p class="text-gray-700 text-sm font-medium"><i class="fas fa-phone-alt mr-1 text-[10px] text-gray-400"></i><?= htmlspecialchars($order['shipping_phone']) ?></p>
                    <?php endif; ?>
                    <p class="text-gray-600 text-sm"><?= htmlspecialchars($order['shipping_address']) ?></p>
                    <?php if (!empty($order['shipping_method'])): ?>
                    <p class="text-gray-500 text-xs mt-1">via <?= htmlspecialchars($order['shipping_method']) ?></p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                <?php if (!empty($order['payment_method'])): ?>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Payment</p>
                    <p class="font-bold text-gray-900"><?= $order['payment_method'] === 'cod' ? t('cash_on_delivery') : t('credit_debit_card') ?></p>
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

    <div class="text-center mt-6 space-x-4 no-print">
        <button onclick="downloadPDF()" class="inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg font-medium transition text-sm">
            <i class="fas fa-download mr-2"></i>Download Receipt (PDF)
        </button>
        <button onclick="window.print()" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium transition text-sm">
            <i class="fas fa-print mr-2"></i>Print Receipt
        </button>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
<script>
function downloadPDF() {
    const { jsPDF } = window.jspdf;

    // Receipt dimensions (80mm width)
    const receiptWidth = 80;

    // Calculate content height dynamically
    const invoiceNumber = <?= json_encode($order['invoice_number']) ?>;
    const orderDate = <?= json_encode(date('M d, Y H:i', strtotime($order['created_at']))) ?>;
    const customerName = <?= json_encode($order['user_name']) ?>;
    const shippingPhone = <?= json_encode($order['shipping_phone'] ?? '') ?>;
    const totalPrice = <?= json_encode(number_format($order['total_price'], 2, '.', '')) ?>;
    const items = <?= json_encode(array_map(fn($i) => [
        'name' => $i['product_name'] . (!empty($i['color_name']) || !empty($i['size_name']) ? ' (' . trim(($i['color_name'] ?? '') . ' ' . ($i['size_name'] ?? '')) . ')' : ''),
        'qty' => (int)$i['quantity'],
        'price' => number_format($i['price'], 2, '.', ''),
        'total' => number_format($i['price'] * $i['quantity'], 2, '.', '')
    ], $items)) ?>;

    // Estimate total height needed
    const headerH = 45;
    const itemH = items.length * 8;
    const footerH = 40;
    const totalHeight = headerH + itemH + footerH + 20;

    // Initialize PDF with custom size
    const doc = new jsPDF('p', 'mm', [receiptWidth, totalHeight]);

    // Content Styling
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(22);
    doc.text('GENERAL ONLINE STORE', receiptWidth / 2, 15, { align: 'center' });
    
    doc.setFont('helvetica', 'normal');
    doc.setFontSize(9);
    doc.text('THANK YOU FOR YOUR VISIT', receiptWidth / 2, 20, { align: 'center' });

    doc.setLineWidth(0.5);
    doc.line(5, 25, 75, 25);

    // Info Section
    doc.setFontSize(8);
    let currY = 32;
    doc.text('Receipt #:', 5, currY);
    doc.setFont('helvetica', 'bold');
    doc.text(invoiceNumber, 75, currY, { align: 'right' });

    doc.setFont('helvetica', 'normal');
    currY += 5;
    doc.text('Date:', 5, currY);
    doc.text(orderDate, 75, currY, { align: 'right' });

    currY += 5;
    doc.text('Customer:', 5, currY);
    doc.text(customerName, 75, currY, { align: 'right' });

    if (shippingPhone) {
        currY += 5;
        doc.text('Phone:', 5, currY);
        doc.text(shippingPhone, 75, currY, { align: 'right' });
    }

    currY += 5;
    doc.setLineWidth(0.1);
    doc.line(5, currY, 75, currY);

    // Items Table
    doc.autoTable({
        startY: currY + 2,
        margin: { left: 5, right: 5 },
        head: [['DESCRIPTION', 'QTY', 'AMOUNT']],
        body: items.map(i => [i.name.toUpperCase(), i.qty, '$' + i.total]),
        theme: 'plain',
        styles: { fontSize: 7, cellPadding: 2, font: 'helvetica' },
        headStyles: { fontStyle: 'bold', textColor: [100, 100, 100] },
        columnStyles: {
            0: { cellWidth: 40 },
            1: { halign: 'center', cellWidth: 10 },
            2: { halign: 'right', cellWidth: 20 }
        }
    });

    // Totals Section
    currY = doc.lastAutoTable.finalY + 5;
    doc.setLineWidth(0.5);
    doc.line(5, currY, 75, currY);

    currY += 7;
    doc.setFontSize(9);
    doc.setFont('helvetica', 'normal');
    doc.text('Subtotal', 5, currY);
    doc.text('$' + totalPrice, 75, currY, { align: 'right' });

    currY += 8;
    doc.setLineWidth(1);
    doc.line(5, currY - 2, 75, currY - 2);
    doc.setFontSize(14);
    doc.setFont('helvetica', 'bold');
    doc.text('TOTAL', 5, currY + 4);
    doc.text('$' + totalPrice, 75, currY + 4, { align: 'right' });

    // Footer
    currY += 20;
    doc.setFontSize(8);
    doc.setFont('helvetica', 'normal');
    doc.text('Items once sold are non-refundable.', receiptWidth / 2, currY, { align: 'center' });
    doc.text('www.generalonlinestore.com', receiptWidth / 2, currY + 5, { align: 'center' });
    doc.setFont('helvetica', 'bold');
    doc.text('SEE YOU AGAIN!', receiptWidth / 2, currY + 12, { align: 'center' });

    // Save
    doc.save('Receipt-' + invoiceNumber + '.pdf');
}
</script>
