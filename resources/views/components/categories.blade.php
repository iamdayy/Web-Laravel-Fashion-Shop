     <!-- Categories Start -->
     <div class="container pt-5">
         <h2 class="text-uppercase fs-5 text-secondary">Categories</h2>
         <hr width="70px">
         <div class="pb-3 row">
             @foreach ($categories as $category)
                 <?php
                 $category = (object) $category; // Convert to object for easier access
                 ?>
                 <div class="pb-1 col-lg-4 col-md-4 col-sm-6">
                     <a class="text-decoration-none text-secondary"
                         href="/products/{{ strtolower($category->category) }}">
                         <div
                             class="justify-center gap-2 p-3 mb-4 bg-white shadow-sm cat-item d-flex align-items-center rounded-3">
                             <div class="overflow-hidden" style="width: 100px; height: 100px;">
                                 <img class="img-fluid placeholder placeholder-glow" src="{{ asset($category->photo) }}"
                                     alt="{{ ucfirst($category->category) }} Image">
                             </div>
                             <div class="ml-4 flex-fill">
                                 <h6>{{ ucfirst($category->category) }}</h6>
                                 <small class="text-body">{{ $category->products_count }} Products</small>
                             </div>
                         </div>
                     </a>
                 </div>
             @endforeach
         </div>
     </div>
     <!-- Categories End -->
