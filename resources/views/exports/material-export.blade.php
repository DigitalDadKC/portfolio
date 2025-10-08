<div class="grid grid-flow-col justify-stretch">
    <table class="table-auto">
        <thead>
            <tr>
                <th>Contract:</th>
                <th>{{$contract['Name']}}</th>
                <th></th>
                <th></th>
                <th></th>
                <th>Effective Date:</th>
                <th>{{date('F, j Y', strtotime($effective_date))}}</th>
            </tr>
            <tr>
                <th>Contract #</th>
                <th>{{$contract['Contract_No']}}</th>
                <th></th>
                <th></th>
                <th></th>
                <th>Discount:</th>
                <th>{{$contract['Discount']}}</th>
            </tr>
            <tr></tr>
            <tr>
                <th>SKU</th>
                <th>Name</th>
                <th>Unit</th>
                <th>Price</th>
                <th>Status</th>
                <th>Discountable</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody id="cooperative-table-body">
            @foreach($materials as $material)
                <tr>
                    <td class="p-2 text-center">{{$material->SKU}}</td>
                    <td class="p-2">{{$material->Name}}</td>
                    <td class="p-2 text-center">{{optional($material->materialUnitSize)->Unit_Size}}</td>
                    <td class="p-2 text-center">{{$material[$effective_date]}}</td>
                    <td class="p-2 text-center">{{$material[$effective_date . '-status']}}</td>
                    <td>{{($material->Discountable) ? "YES" : "NO"}}</td>
                    <td class="p-2 text-center">{{optional($material->materialCategory)->Name}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>