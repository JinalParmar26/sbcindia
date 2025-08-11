<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PublicStaffController extends Controller
{
    /**
     * Display staff visiting card - public access
     */
    public function visitingCard($uuid)
    {
        try {
            $staff = User::where('uuid', $uuid)
                ->where('isActive', true)
                ->firstOrFail();
            
            return view('public.staff-visiting-card', compact('staff'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->view('errors.404', [
                'message' => 'Staff member not found or inactive.',
                'uuid' => $uuid
            ], 404);
        }
    }
    
    /**
     * Display staff profile - public access with more details
     */
    public function profile($uuid)
    {
        try {
            $staff = User::where('uuid', $uuid)
                ->where('isActive', true)
                ->firstOrFail();
            
            return view('public.staff-profile', compact('staff'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->view('errors.404', [
                'message' => 'Staff member not found or inactive.',
                'uuid' => $uuid
            ], 404);
        }
    }

    public function downloadVcf($uuid)
    {
        try {
            $user = User::where('uuid', $uuid)
                    ->where('isActive', true)
                    ->firstOrFail();

            // Build staff details for vCard
            $staff = [
                'name' => $user->name,
                'org' => 'SBC Cooling Systems',
                'title' => $user->designation ?? 'Staff Member',
                'phone' => $user->phone_number,
                'email' => $user->email,
                'url' => 'https://sbccindia.com/',
                'address' => '123 Industrial Area, Phase-II, Chandigarh, 160002, India',
                'note' => 'Industrial Cooling Solutions Excellence - SBC Cooling Systems'
            ];

            // Build vCard text with proper formatting
            $vcard = "BEGIN:VCARD\r\n";
            $vcard .= "VERSION:3.0\r\n";
            $vcard .= "FN:" . $this->escapeVcardValue($staff['name']) . "\r\n";
            
            if (!empty($staff['name'])) {
                $nameParts = explode(' ', $staff['name'], 2);
                $firstName = $nameParts[0] ?? '';
                $lastName = $nameParts[1] ?? '';
                $vcard .= "N:" . $this->escapeVcardValue($lastName) . ";" . $this->escapeVcardValue($firstName) . ";;;\r\n";
            }
            
            $vcard .= "ORG:" . $this->escapeVcardValue($staff['org']) . "\r\n";
            $vcard .= "TITLE:" . $this->escapeVcardValue($staff['title']) . "\r\n";
            
            if (!empty($staff['phone'])) {
                $vcard .= "TEL;TYPE=WORK,VOICE:" . $this->escapeVcardValue($staff['phone']) . "\r\n";
            }
            
            if (!empty($staff['email'])) {
                $vcard .= "EMAIL;TYPE=WORK:" . $this->escapeVcardValue($staff['email']) . "\r\n";
            }
            
            $vcard .= "URL:" . $this->escapeVcardValue($staff['url']) . "\r\n";
            
            // Address format: ;;Street;City;State;PostalCode;Country
            $vcard .= "ADR;TYPE=WORK:;;123 Industrial Area\\, Phase-II;Chandigarh;;160002;India\r\n";
            
            $vcard .= "NOTE:" . $this->escapeVcardValue($staff['note']) . "\r\n";
            
            // Add creation timestamp
            $vcard .= "REV:" . date('Y-m-d\TH:i:s\Z') . "\r\n";
            
            $vcard .= "END:VCARD\r\n";

            // Create safe filename
            $safeFileName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $staff['name']);
            
            // Return as downloadable vCard
            return response($vcard, 200)
                ->header('Content-Type', 'text/vcard; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="' . $safeFileName . '_Contact.vcf"')
                ->header('Cache-Control', 'no-cache, must-revalidate')
                ->header('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');
                
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->view('errors.404', [
                'message' => 'Staff member not found or inactive.',
                'uuid' => $uuid
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Unable to generate contact card.',
                'message' => 'Please try again later.'
            ], 500);
        }
    }

    /**
     * Escape special characters for vCard values
     */
    private function escapeVcardValue($value)
    {
        if (empty($value)) {
            return '';
        }
        
        // Escape special vCard characters
        $value = str_replace(['\\', ';', ',', "\n", "\r"], ['\\\\', '\\;', '\\,', '\\n', '\\n'], $value);
        return $value;
    }
}
