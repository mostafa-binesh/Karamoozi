<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;

class DevToolController extends Controller
{
    public function notFound()
    {
        return back()->with(['errors' => ['not found']]);
        return 1;
    }
    public function error($error)
    {
        return back()->with(['errors' => [$error]]);
        return 2;
    }
    public function successful()
    {
        return back()->with(['errors' => ['successful']]);
        return back()->with(['messages' => ['successful']]);
        return 3;
    }
    public function handler(Request $req)
    {
        if (isset($req->verifyIndustrySupervisor)) {
            $i = User::where('username', $req->in)->first();
            // $i ?? notFound();
            if (!isset($i) || $i == null) return $this->notFound();
            $i = $i->industrySupervisor;
            if (!isset($i) || $i == null) return $this->notFound();
            try {
                $i->verified = 1;
                $i->save();
            } catch (Exception $e) {
                return $this->error($e->getMessage());
            }
            return $this->successful();
        }
        if (isset($req->unverifyIndustrySupervisor)) {
            $i = User::where('username', $req->in)->first();
            if (!isset($i) || $i == null) return $this->notFound();
            $i = $i->industrySupervisor;
            if (!isset($i) || $i == null) return $this->notFound();
            try {
                $i->verified = 0;
                $i->save();
            } catch (Exception $e) {
                return $this->error($e->getMessage());
            }
            return $this->successful();
        }
        if (isset($req->deleteIndustryOfStudent)) {
            $i = Student::where('student_number', $req->in)->first();
            if (!isset($i) || $i == null) return $this->notFound();
            try {
                $i->supervisor_id = null;
                $i->save();
            } catch (Exception $e) {
                return $this->error($e->getMessage());
            }
            return $this->successful();
        }
        if (isset($req->unevaluateStudent)) {
            $i = Student::where('student_number', $req->in)->first();
            if (!isset($i) || $i == null) return $this->notFound();
            try {
                $i->unevaluate();
                $i->save();
            } catch (Exception $e) {
                return $this->error($e->getMessage());
            }
            return $this->successful();
        }
        if (isset($req->workingStudent)) {
            $i = Student::where('student_number', $req->in)->first();
            if (!isset($i) || $i == null) return $this->notFound();
            try {
                $i->working();
                $i->save();
            } catch (Exception $e) {
                return $this->error($e->getMessage());
            }
            return $this->successful();
        }
        if (isset($req->evaluateStudent)) {
            $i = Student::where('student_number', $req->in)->first();
            if (!isset($i) || $i == null) return $this->notFound();
            try {
                $i->evaluate();
                $i->save();
            } catch (Exception $e) {
                return $this->error($e->getMessage());
            }
            return $this->successful();
        }
        if (isset($req->verifyStudent)) {
            $i = Student::where('student_number', $req->in)->first();
            if (!isset($i) || $i == null) return $this->notFound();
            try {
                $i->verified = true;
                $i->save();
            } catch (Exception $e) {
                return $this->error($e->getMessage());
            }
            return $this->successful();
        }
        if (isset($req->unVerifyStudent)) {
            $i = Student::where('student_number', $req->in)->first();
            if (!isset($i) || $i == null) return $this->notFound();
            try {
                $i->verified = false;
                $i->save();
            } catch (Exception $e) {
                return $this->error($e->getMessage());
            }
            return $this->successful();
        }
        if (isset($req->doStudentPreReg)) {
            $i = Student::where('student_number', $req->in)->first();
            if (!isset($i) || $i == null) return $this->notFound();
            try {
                $i->pre_reg_done = true;
                $i->save();
            } catch (Exception $e) {
                return $this->error($e->getMessage());
            }
            return $this->successful();
        }
        if (isset($req->unDoStudentPreReg)) {
            $i = Student::where('student_number', $req->in)->first();
            if (!isset($i) || $i == null) return $this->notFound();
            try {
                $i->pre_reg_done = false;
                $i->save();
            } catch (Exception $e) {
                return $this->error($e->getMessage());
            }
            return $this->successful();
        }
        if (isset($req->unDoStudentPreReg)) {
            $i = Student::where('student_number', $req->in)->first();
            if (!isset($i) || $i == null) return $this->notFound();
            try {
                $i->pre_reg_done = false;
                $i->save();
            } catch (Exception $e) {
                return $this->error($e->getMessage());
            }
            return $this->successful();
        }
    }
}
