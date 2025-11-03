import { ref } from 'vue';
import { useDeepCopy } from '@/composables/useDeepCopy';

let { deepCopy } = useDeepCopy()

export function useProjectMath(form) {
    // let ref_line = { 'id': null, 'description': '', 'unit_of_measurement': { 'id': null, 'UOM': '' }, 'price': null, 'quantity': null, 'total': null, 'days': null };
    // let ref_scope = { 'id': null, 'name': '', 'area': null, 'days': null, 'total': null, 'lines': [] }

    // const lineTotal = (scopeIndex, lineIndex) => {
    //     scopeTotal(scopeIndex)
    //     let line = job.scopes[scopeIndex].lines[lineIndex]
    //     return line.total = +(Math.round(line?.price * line?.quantity*100)/100)
    // }

    // const scopeTotal = (scopeIndex) => {
    //     jobTotal()
    //     let scope = job.scopes[scopeIndex]
    //     return scope.total = (Math.round(job.scopes[scopeIndex].lines.map(line => line.price * line.quantity).reduce((a, b) => a + b, 0)*100)/100).toLocaleString()
    // }

    // const jobTotal = () => {
    //     return job.total = (Math.round(job.scopes.map(scope => scope.lines.map(line => line.price*line.quantity).reduce((a, b) => a + b, 0)).reduce((a, b) => a + b, 0)*100)/100).toLocaleString()
    // }

    // const updateScopeDays = () => {
    //     job.scopes.map(scope => scope.days = parseInt(scope.lines.reduce((a, b) => a + b.days, 0)) ?? 0)
    //     job.days = job.scopes.map(scope => scope.days).reduce((a, b) => a + b, 0)
    // }

    const fillQuantity = (scopeIndex, index) => {
        form.proposal.scopes[scopeIndex].lines[index].quantity = form.proposal.scopes[scopeIndex].area
    }

    const totals = () => {
        console.log(form)
        form.proposal.scopes.map(scope => scope.lines.map(line => line.total = ((line.price * line.quantity*100)/100)))
        form.proposal.scopes.map(scope => scope.total = scope.lines.map(line => ((line.price * line.quantity*100)/100)).reduce((a, b) => a + b, 0))
        form.proposal.total = form.proposal.scopes.map(scope => scope.lines.map(line => ((line.price * line.quantity*100)/100)).reduce((a, b) => a + b, 0)).reduce((a, b) => a + b, 0)
    }

    return { fillQuantity, totals}
}
