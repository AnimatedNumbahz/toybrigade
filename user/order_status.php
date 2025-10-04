<?php
// /opt/lampp/htdocs/toybrigade/config/order_status.php
// Centralized order status definitions and helpers

// Allowed pipeline values (keep in sync with your DB ENUM)
const TB_ORDER_STATUSES = [
    'PENDING',
    'PROCESSING',
    'SHIPPED',
    'DELIVERED',
    'CANCELLED',
    'RETURN_REQUESTED',
    'RETURNED',
];

// Human-friendly display labels
const TB_ORDER_STATUS_LABELS = [
    'PENDING'          => 'Pending',
    'PROCESSING'       => 'Processing',
    'SHIPPED'          => 'Shipped',
    'DELIVERED'        => 'Delivered',
    'CANCELLED'        => 'Cancelled',
    'RETURN_REQUESTED' => 'Return Requested',
    'RETURNED'         => 'Returned',
];

// Minimal badge “type” for quick styling
const TB_ORDER_STATUS_BADGE = [
    'PENDING'          => 'badge-warning',
    'PROCESSING'       => 'badge-info',
    'SHIPPED'          => 'badge-primary',
    'DELIVERED'        => 'badge-success',
    'CANCELLED'        => 'badge-danger',
    'RETURN_REQUESTED' => 'badge-warning',
    'RETURNED'         => 'badge-neutral',
];

// Validate status against allowed set
function tb_is_valid_status(?string $status): bool {
    return $status !== null && in_array($status, TB_ORDER_STATUSES, true);
}

// Get a readable label
function tb_status_label(?string $status): string {
    if (!tb_is_valid_status($status)) return 'Unknown';
    return TB_ORDER_STATUS_LABELS[$status] ?? $status;
}

// Get a badge class for a status
function tb_status_badge(?string $status): string {
    if (!tb_is_valid_status($status)) return 'badge-neutral';
    return TB_ORDER_STATUS_BADGE[$status] ?? 'badge-neutral';
}

// Provide a simple ordered progression for UI timelines
function tb_status_index(?string $status): int {
    $map = array_values(TB_ORDER_STATUSES);
    $idx = array_search($status, $map, true);
    return $idx === false ? -1 : $idx;
}
