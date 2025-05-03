<?php
 namespace App\Filters;

 use Illuminate\Database\Eloquent\Builder;


 /**
  * PostFilter class for applying filters to Post queries.
  *
  * This class is responsible for filtering Post queries based on various criteria such as
  * whether the post is published, a search term for title and body, and tags.
  * It utilizes the query builder to conditionally apply filters based on the provided filters array.
  */

 class PostFilter
 {
     protected array $filters = [];

     public function __construct(array $filters = [])
     {
         $this->filters = $filters;
     }

     /**
      * Applies the filters to the given query builder.
      *
      * This method will modify the given query based on the provided filters and return the modified query.
      * It checks for various filters such as 'is_published', 'search', and 'tags' to modify the query accordingly.
      *
      * @param Builder $query The query builder instance to which filters will be applied.
      *
      * @return Builder The modified query builder instance with applied filters.
      */
     public function apply(Builder $query): Builder
     {
         $search = '%' . $this->filters['search'] . '%';

         return $query->when(isset($this->filters['is_published']),
         fn($q)=> $q->where('is_published' , $this->filters['is_published']))
             ->when(isset($this->filters['search']),
             fn($q)=>$q->where('title', 'like', $search)
             ->orWhere('body', 'like', $search))
             ->when(isset($this->filters['tags']), fn($q) =>
             $q->whereJsonContains('tags', $this->filters['tags']));

     }
 }
