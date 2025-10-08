export function useFormatCurrency() {

    function formatWithCommas(value, type = 'text') {
    if (value === null || value === '') return ''
    switch (true) {
        case type === 'text':
            return value?.toString()
        case type === 'number':
            return Intl.NumberFormat('en-US').format(value)
        case type === 'currency':
            return Number(value).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
                style: 'currency',
                currency: 'USD'
            })
        case type === 'percent':
            return Number(value/100).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 4,
                style: 'percent'
            })
        case type === 'date':
            return new Date(value).toLocaleDateString('en-US', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
            })
        }
    }

    return { formatWithCommas }
}
