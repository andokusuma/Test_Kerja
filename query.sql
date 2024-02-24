SELECT
    invoice.id,
    invoice.customer_name,
    invoice.delivery_date,
    invoice.no_invoice,
    SUM(detail_invoice.price) as total_price    
FROM
    invoice
JOIN
    detail_invoice ON invoice.id = detail_invoice.id_invoice
GROUP BY
    invoice.id, invoice.customer_name, invoice.delivery_date, invoice.no_invoice;

    
