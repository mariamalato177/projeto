<div {{ $attributes }}>
        <div class="flex justify-between space-x-3">
            <div class="grow flex flex-col space-y-2">
                <div>
                    <x-field.input name="name" label="Name" class="grow" id="inputName" placeholder="Search by Name" form="pesq"
                        value="{{ $name }}"/>
                </div>
            </div>
            <div class="grow-0 flex flex-col space-y-3 justify-start">
                <div class="pt-6">
                    <x-button element="submit" type="dark" text="Filter" form="pesq"/>
                </div>
                <div>
                    <x-button element="a" type="light" text="Cancel" :href="$resetUrl"/>
                </div>
            </div>
            <form action="{{route('customers.index')}}" id="pesq">
                @csrf
            </form>
        </div>
</div>
