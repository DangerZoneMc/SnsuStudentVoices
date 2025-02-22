<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Inertia\Inertia;
use Inertia\Response;

class OrganizationController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Organizations/Index', [
            'organizations' => Organization::all()
                ->map(function ($org) {
                    return [
                        'id' => $org->id,
                        'name' => $org->name,
                        'description' => $org->description,
                        'college' => $org->college,
                    ];
                }),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:1024',
        ]);

        $organization = new Organization($validated);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('organizations', 'public');
            $organization->logo_url = '/storage/' . $path;
        }

        $request->user()->organizations()->save($organization);

        return redirect(route('organizations'));
    }
} 