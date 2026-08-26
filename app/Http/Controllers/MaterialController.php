<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Material;
use App\Models\Unit;
use Illuminate\Http\Request;

// FR-03 (Supply kelola material), FR-04 (Demand cari & lihat material)
class MaterialController extends Controller
{
    // Listing untuk Demand: cari & filter material yang tersedia
    public function index(Request $request)
    {
        $materials = Material::query()
            ->with(["supply", "category", "unit"])
            ->available()
            ->when($request->search, fn ($q) => $q->where("name", "like", "%{$request->search}%"))
            ->when($request->category_id, fn ($q) => $q->where("category_id", $request->category_id))
            ->when($request->location, fn ($q) => $q->where("location", "like", "%{$request->location}%"))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $categories = Category::where("is_active", true)->orderBy("name")->get();

        $recommended = auth()->user()->isDemand()
            ? Material::with(["supply", "category", "unit"])->recommendedFor(auth()->user())->take(4)->get()
            : collect();

        return view("materials.index", compact("materials", "categories", "recommended"));
    }

    public function show(Material $material)
    {
        $material->load(["supply", "category", "unit"]);

        return view("materials.show", compact("material"));
    }

    // Halaman kelola material milik Supply sendiri
    public function myMaterials()
    {
        $materials = auth()->user()->materials()->with(["category", "unit"])->latest()->paginate(10);

        return view("materials.my", compact("materials"));
    }

    public function create()
    {
        $categories = Category::where("is_active", true)->orderBy("name")->get();
        $units = Unit::orderBy("name")->get();

        return view("materials.create", compact("categories", "units"));
    }

    public function store(Request $request)
    {
        $data = $this->validateMaterial($request);
        $data["supply_id"] = auth()->id();

        if ($request->hasFile("photo")) {
            $data["photo_path"] = $request->file("photo")->store("materials", "public");
        }

        Material::create($data);

        return redirect()->route("materials.my")->with("success", "Material berhasil ditambahkan.");
    }

    public function edit(Material $material)
    {
        $this->authorizeOwnership($material);

        $categories = Category::where("is_active", true)->orderBy("name")->get();
        $units = Unit::orderBy("name")->get();

        return view("materials.edit", compact("material", "categories", "units"));
    }

    public function update(Request $request, Material $material)
    {
        $this->authorizeOwnership($material);

        $data = $this->validateMaterial($request);

        if ($request->hasFile("photo")) {
            $data["photo_path"] = $request->file("photo")->store("materials", "public");
        }

        $material->update($data);

        return redirect()->route("materials.my")->with("success", "Material berhasil diperbarui.");
    }

    public function destroy(Material $material)
    {
        $this->authorizeOwnership($material);
        $material->delete();

        return back()->with("success", "Material berhasil dihapus.");
    }

    private function validateMaterial(Request $request): array
    {
        return $request->validate([
            "category_id" => ["required", "exists:categories,id"],
            "unit_id" => ["required", "exists:units,id"],
            "name" => ["required", "string", "max:255"],
            "description" => ["nullable", "string"],
            "quantity" => ["required", "numeric", "min:0.01"],
            "location" => ["required", "string", "max:255"],
            "available_from" => ["nullable", "date"],
            "available_until" => ["nullable", "date", "after_or_equal:available_from"],
            "status" => ["required", "in:available,unavailable"],
            "photo" => ["nullable", "image", "max:2048"],
        ]);
    }

    private function authorizeOwnership(Material $material): void
    {
        abort_unless($material->supply_id === auth()->id(), 403);
    }
}