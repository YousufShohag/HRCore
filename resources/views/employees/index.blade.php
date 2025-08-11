@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">👥 Employee Directory</h2>
        <div>
            <a href="{{ route('employees.create') }}" class="btn btn-primary me-2">
                <i class="bi bi-plus-circle"></i> Add Employee
            </a>
            <a href="{{ route('dashboard.index') }}" class="btn btn-success">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>


    {{-- @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif --}}

    <!-- Filters -->
    <form method="GET" action="{{ route('employees.index') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="name" value="{{ request('name') }}" class="form-control" placeholder="🔍 Name">
        </div>
        <div class="col-md-3">
            <input type="text" name="designation" value="{{ request('designation') }}" class="form-control" placeholder="🎓 Designation">
        </div>
        <div class="col-md-3">
            <input type="text" name="department" value="{{ request('department') }}" class="form-control" placeholder="🏢 Department">
        </div>
        <div class="col-md-2">
            <input type="number" step="0.01" name="salary" value="{{ request('salary') }}" class="form-control" placeholder="💰 Salary =">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">📋 All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>🟢 Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>🔴 Inactive</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-outline-primary w-100"><i class="bi bi-funnel-fill"></i>Filter</button>
            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary w-100"><i class="bi bi-arrow-clockwise">Clear</i></a>
        </div>
    </form>

    <!-- Employee Table -->
    @if($employees->count())
    <div class="card shadow-sm">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-left">
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Designation</th>
                        {{-- <th>Leave</th> --}}
                        {{-- <th>Department</th> --}}
                        <th>Bank A/C</th>
                        <th>Salary</th>
                        <th>Join Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                        {{-- <th>Attandance</th> --}}
                    </tr>
                </thead>
                <tbody class="text-left">
                    @foreach($employees as $index => $employee)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @if($employee->image)
                                <img src="{{ asset('storage/' . $employee->image) }}" class="rounded-circle" width="45" height="45" style="object-fit: cover;">
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>{{ $employee->name }} <br><a href="{{ route('employee.id-card', $employee->id) }}" target="_blank" class="btn btn-sm btn-outline-primary">
    Generate ID Card
</a></td>

                        

                        <td>{{ $employee->designation }}</td>
                        {{-- <td>{{ $employee->leave_balance }}</td> --}}
                        {{-- <td>{{ $employee->department ?? 'N/A' }}</td> --}}
                        <td>{{ $employee->bank_account_number ?? 'N/A' }}</td>
                        <td>{{ number_format($employee->basic_salary, 2) }}</td>
                        <td> {{ $employee->join_date ? \Carbon\Carbon::parse($employee->join_date)->format('m/d/Y') : 'N/A' }}</td>

                        <td>
                            @if(!$employee->resign_date || \Carbon\Carbon::parse($employee->resign_date)->isFuture())
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <button 
                                class="btn btn-sm btn-outline-primary edit-btn"
                                data-bs-toggle="modal" 
                                data-bs-target="#editModal"
                                data-id="{{ $employee->id }}"
                                data-name="{{ $employee->name }}"
                                data-designation="{{ $employee->designation }}"
                                data-department="{{ $employee->department }}"
                                data-basic_salary="{{ $employee->basic_salary }}"
                                data-join_date="{{ $employee->join_date ? $employee->join_date->format('Y-m-d') : '' }}"
                                data-resign_date="{{ $employee->resign_date ? $employee->resign_date->format('Y-m-d') : '' }}"
                                data-image="{{ $employee->image ? asset('storage/' . $employee->image) : '' }}"
                            >
                                <i class="bi bi-pencil-square">s</i>
                            </button>

                            <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                            <button 
                                type="button" 
                                class="btn btn-sm btn-outline-info view-btn"
                                data-bs-toggle="modal" 
                                data-bs-target="#viewModal"
                                data-name="{{ $employee->name }}"
                                data-designation="{{ $employee->designation }}"
                                data-department="{{ $employee->department }}"
                                data-bank="{{ $employee->bank_account_number }}"
                                data-salary="{{ number_format($employee->basic_salary, 2) }}"
                                data-join="{{ optional($employee->join_date)->format('m-d-y') }}"
                                data-resign="{{ optional($employee->resign_date)->format('Y-m-d') }}"
                                data-leave_balance="{{ $employee->leave_balance }}"
                                data-image="{{ $employee->image ? asset('storage/' . $employee->image) : '' }}"
                                data-document="{{ $employee->document ? asset('storage/' . $employee->document) : '' }}"
                            >
                                <i class="bi bi-eye"></i> View
                            </button>


                        </td>
                         <td>
                        <!-- Your existing edit/delete/view buttons -->

                       <button
    type="button"
    class="btn btn-sm btn-success clock-btn"
    data-bs-toggle="modal"
    data-bs-target="#attendanceModal"
    data-employee-id="{{ $employee->id }}"
    data-employee-name="{{ $employee->name }}"
    data-action-type="clock_in"
    @if($employee->attendanceToday && $employee->attendanceToday->clock_in) disabled @endif
>
    Clock In
</button>

<button
    type="button"
    class="btn btn-sm btn-danger clock-btn"
    data-bs-toggle="modal"
    data-bs-target="#attendanceModal"
    data-employee-id="{{ $employee->id }}"
    data-employee-name="{{ $employee->name }}"
    data-action-type="clock_out"
    @if(!$employee->attendanceToday || $employee->attendanceToday->clock_out) disabled @endif
>
    Clock Out
</button>

                    </td> 

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
        <div class="alert alert-info mt-3">
            <i class="bi bi-info-circle"></i> No employees found.
        </div>
    @endif
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" id="editForm" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold">Edit Employee</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="id" id="edit-id">
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" id="edit-name" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Designation</label>
                <input type="text" name="designation" id="edit-designation" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Department</label>
                <input type="text" name="department" id="edit-department" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Basic Salary</label>
                <input type="number" step="0.01" name="basic_salary" id="edit-basic_salary" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Join Date</label>
                <input type="date" name="join_date" id="edit-join_date" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Resign Date</label>
                <input type="date" name="resign_date" id="edit-resign_date" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Current Image</label><br>
                <img id="edit-image-preview" src="" alt="No Image" class="rounded-circle border" width="100" height="100" style="object-fit: cover;">
            </div>
            <div class="mb-3">
                <label class="form-label">Update Image</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
            <div class="mb-3">
                <label class="form-label">Update Document</label>
                <input type="file" name="document" class="form-control" accept=".pdf,.doc,.docx,.txt">
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">
              <i class="bi bi-save2"></i> Update
          </button>
        </div>
      </div>
    </form>
  </div>
</div>


<!-- View Details Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="viewModalLabel">Employee Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-4 text-center">
                <img id="view-image" src="" alt="No Image" class="mt-4 border mb-3" width="180" height="200" style="">
            </div>
            <div class="col-md-8">
                <p><strong>Name:</strong> <span id="view-name"></span></p>
                <p><strong>Designation:</strong> <span id="view-designation"></span></p>
                <p><strong>Department:</strong> <span id="view-department"></span></p>
                <p><strong>Bank A/C:</strong> <span id="view-bank"></span></p>
                <p><strong>Salary:</strong> ৳<span id="view-salary"></span></p>
                <p><strong>Join Date:</strong> <span id="view-join"></span></p>
                <p><strong>Resign Date:</strong> <span id="view-resign"></span></p>
                <p><strong>Leave Balance:</strong> <span id="view-leave_balance"></span></p>
                
            </div>
            <div class="mt-4">
                <h6><i class="bi bi-file-earmark-text"></i> Document:</h6>
                <div id="view-document-area">
                    <p class="text-muted">No document available.</p>
                </div>
            </div>

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle"></i> Close
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Attendance Modal -->
<div class="modal fade" id="attendanceModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="attendanceForm" method="POST" enctype="multipart/form-data" action="{{ route('attendance.store', $employee->id) }}">
      @csrf
      <input type="hidden" name="attendance_type" id="attendance_type" value="">
      <input type="hidden" name="photo" id="photo_input" required>
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Attendance for <span id="attendanceEmployeeName"></span></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">
          <video id="video" width="320" height="240" autoplay></video>
          <canvas id="canvas" width="320" height="240" style="display:none;"></canvas>
          <div class="mt-3">
            <button type="button" id="snapBtn" class="btn btn-primary">Take Photo</button>
          </div>
          <div class="mt-3">
            <img id="photoPreview" src="" alt="Photo Preview" style="display:none; max-width: 100%;">
          </div>
          <p><small>You must take a photo to submit attendance.</small></p>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success" id="submitBtn" disabled>Submit Attendance</button>
        </div>
      </div>
    </form>
  </div>
</div>


@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const buttons = document.querySelectorAll(".edit-btn");
        buttons.forEach(button => {
            button.addEventListener("click", function () {
                document.getElementById("edit-id").value = this.dataset.id;
                document.getElementById("edit-name").value = this.dataset.name;
                document.getElementById("edit-designation").value = this.dataset.designation;
                document.getElementById("edit-department").value = this.dataset.department;
                document.getElementById("edit-basic_salary").value = this.dataset.basic_salary;
                document.getElementById("edit-join_date").value = this.dataset.join_date;
                document.getElementById("edit-resign_date").value = this.dataset.resign_date;

                const imagePreview = document.getElementById("edit-image-preview");
                if (this.dataset.image) {
                    imagePreview.src = this.dataset.image;
                    imagePreview.style.display = 'inline-block';
                } else {
                    imagePreview.src = '';
                    imagePreview.style.display = 'none';
                }

                document.getElementById("editForm").action = `/employees/${this.dataset.id}`;
            });
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".view-btn").forEach(button => {
            button.addEventListener("click", function () {
                document.getElementById("view-name").textContent = this.dataset.name;
                document.getElementById("view-designation").textContent = this.dataset.designation;
                document.getElementById("view-department").textContent = this.dataset.department;
                document.getElementById("view-bank").textContent = this.dataset.bank;
                document.getElementById("view-salary").textContent = this.dataset.salary;
                document.getElementById("view-join").textContent = this.dataset.join || 'N/A';
                document.getElementById("view-resign").textContent = this.dataset.resign || 'N/A';

                // Show Leave Balance here:
            document.getElementById("view-leave_balance").textContent = this.dataset.leave_balance + ' days';

                const image = this.dataset.image;
                const imageTag = document.getElementById("view-image");
                if (image) {
                    imageTag.src = image;
                    imageTag.style.display = 'inline-block';
                } else {
                    imageTag.src = '';
                    imageTag.style.display = 'none';
                }

                const docArea = document.getElementById("view-document-area");
                const docLink = this.dataset.document;

                if (docLink) {
                    const ext = docLink.split('.').pop().toLowerCase();
                    if (ext === 'pdf') {
                        docArea.innerHTML = `<iframe src="${docLink}" class="w-100" height="300" style="border:1px solid #ccc;"></iframe>`;
                    } else {
                        docArea.innerHTML = `<a href="${docLink}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-download"></i> Download Document
                        </a>`;
                    }
                } else {
                    docArea.innerHTML = `<p class="text-muted">No document available.</p>`;
                }
            });
        });
    });
    document.addEventListener('DOMContentLoaded', function () {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const snapBtn = document.getElementById('snapBtn');
    const photoInput = document.getElementById('photo_input');
    const photoPreview = document.getElementById('photoPreview');
    const submitBtn = document.getElementById('submitBtn');
    const attendanceForm = document.getElementById('attendanceForm');
    const attendanceModal = document.getElementById('attendanceModal');
    const attendanceEmployeeName = document.getElementById('attendanceEmployeeName');
    const attendanceTypeInput = document.getElementById('attendance_type');

    let stream;
    let currentEmployeeId = null;
    let currentActionType = null;

    // When a clock in/out button is clicked
    document.querySelectorAll(".clock-btn").forEach(button => {
        button.addEventListener('click', function () {
            currentEmployeeId = this.dataset.employeeId;
            currentActionType = this.dataset.actionType;
            attendanceEmployeeName.textContent = this.dataset.employeeName;
            attendanceTypeInput.value = currentActionType;

            attendanceForm.action = `/employees/${currentEmployeeId}/attendance`;

            submitBtn.disabled = true;
            photoPreview.style.display = 'none';
            photoPreview.src = '';
            photoInput.value = '';

            // Start camera
            navigator.mediaDevices.getUserMedia({ video: true })
              .then(s => {
                stream = s;
                video.srcObject = stream;
              })
              .catch(() => alert('Cannot access camera'));
        });
    });

    // Stop camera on modal hide
    attendanceModal.addEventListener('hidden.bs.modal', function () {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
    });

    snapBtn.addEventListener('click', function () {
        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        const imageData = canvas.toDataURL('image/png');
        photoPreview.src = imageData;
        photoPreview.style.display = 'block';
        photoInput.value = imageData.split(',')[1];
        submitBtn.disabled = false;
    });
});

</script>


@endsection