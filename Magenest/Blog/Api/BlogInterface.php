<?php
namespace Magenest\Blog\Api;
interface BlogInterface
{
    /**
     * Get list of blogs
     * @return mixed
     */
    public function getBlogs();

    /**
     * Get Blog detail
     * @param string|integer $id
     * @return mixed
     */
    public function getBlogById($id);

    /**
     * Add new blog
     * @return mixed
     */
    public function addBlog();

    /**
     * Update a blog
     * @param string|integer $id
     * @return mixed
     */
    public function updateBlog($id);

    /**
     * Delete a blog
     * @param string|integer $id
     * @return mixed
     */
    public function deleteBlog($id);
}
