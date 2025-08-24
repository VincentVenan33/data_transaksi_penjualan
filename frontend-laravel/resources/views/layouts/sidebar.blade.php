<div id="sidebar" class="d-flex flex-column flex-shrink-0 p-3 bg-dark text-white" style="width: 250px; min-height: 100vh;">
    <h4 class="text-center">Inventory</h4>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ url('/') }}" class="nav-link {{ Request::is('/') ? 'active' : 'text-white' }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/customers') }}" class="nav-link {{ Request::is('customers*') ? 'active' : 'text-white' }}">
                <i class="bi bi-people me-2"></i> Customers
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/products') }}" class="nav-link {{ Request::is('products*') ? 'active' : 'text-white' }}">
                <i class="bi bi-box-seam me-2"></i> Products
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/transactions') }}" class="nav-link {{ Request::is('transactions*') ? 'active' : 'text-white' }}">
                <i class="bi bi-cash-stack me-2"></i> Transactions
            </a>
        </li>
    </ul>
</div>
