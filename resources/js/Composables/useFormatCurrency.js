export function useFormatCurrency() {

    function formatWithCommas(value, type) {
    if (value === null || value === '' || isNaN(value)) return ''
    switch (true) {
        case type === 'text':
            return value.toString()
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
        }
    }

    return { formatWithCommas }
}
