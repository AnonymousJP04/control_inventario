<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Para consultas más complejas si es necesario
use Carbon\Carbon; // Para manejar fechas

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total de productos, clientes, usuarios registrados
        $totalProductos = Producto::count();
        $totalClientes = Cliente::count();
        $totalUsuarios = User::count();

        // 2. Ventas realizadas hoy y monto vendido hoy
        $hoy = Carbon::today();
        $ventasHoy = Venta::whereDate('created_at', $hoy)->count();
        $montoVendidoHoy = Venta::whereDate('created_at', $hoy)->sum('total');

        // 3. Total de descuentos otorgados (desde DetalleVenta)
        $totalDescuentos = DetalleVenta::sum('descuento');
        // Si el descuento está en la tabla Venta, sería:
        // $totalDescuentos = Venta::sum('descuento_total'); // Asumiendo que tienes una columna así

        // 4. Productos con bajo stock
        // Necesitas un método en tu modelo Producto como `stockActual()`
        // y que `stock_minimo` esté definido.
        $productosBajoStock = Producto::all()->filter(function ($producto) {
            // Asumiendo que tienes un método stockActual() en tu modelo Producto
            // que calcula el stock real basado en entradas y salidas.
            // Si no, necesitarás calcularlo aquí o directamente en la consulta.
            // Ejemplo simplificado si 'stock' es una columna directa:
            // return $producto->stock <= $producto->stock_minimo;
            return $producto->stockActual() <= $producto->stock_minimo;
        })->count(); // O puedes pasar la colección completa a la vista

        // 5. Top 5 clientes por monto total de compras
        $topClientes = Cliente::select('clientes.id', 'clientes.nombre', DB::raw('SUM(ventas.total) as monto_total_compras'))
            ->join('ventas', 'clientes.id', '=', 'ventas.cliente_id')
            ->groupBy('clientes.id', 'clientes.nombre')
            ->orderByDesc('monto_total_compras')
            ->limit(5)
            ->get();

        // Otros datos que podrías considerar:
        // - Ventas del mes actual
        $inicioMes = Carbon::now()->startOfMonth(); 
        $finMes = Carbon::now()->endOfMonth();
        $ventasMesActual = Venta::whereBetween('created_at', [$inicioMes, $finMes])->count();
        $montoVendidoMesActual = Venta::whereBetween('created_at', [$inicioMes, $finMes])->sum('total');

        // - Productos más vendidos (requiere más lógica con DetalleVenta)
        $productosMasVendidos = DetalleVenta::select('producto_id', DB::raw('SUM(cantidad) as total_vendido'), 'productos.nombre as nombre_producto')
            ->join('productos', 'detalle_ventas.producto_id', '=', 'productos.id')
            ->groupBy('producto_id', 'productos.nombre')
            ->orderByDesc('total_vendido')
            ->limit(5)
            ->get();


        return view('dashboard', compact(
            'totalProductos',
            'totalClientes',
            'totalUsuarios',
            'ventasHoy',
            'montoVendidoHoy',
            'totalDescuentos',
            'productosBajoStock', // Podrías pasar la colección completa si quieres listarlos
            'topClientes',
            'ventasMesActual',
            'montoVendidoMesActual',
            'productosMasVendidos'
        ));
    }
}