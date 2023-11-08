<?php
use App\Models\Chat;

class ChatRepo
{

    private $chat;
    public function __construct(Chat $chat)
    {
        $this->$chat = $chat;
    }

    public function getAll()
    {
        return $this->chat
            ->orderBy("created_at", "desc")
            ->get();
    }

    public function getById($id)
    {
        return $this->chat
            ->where("id", $id)
            ->first();
    }

    public function create(array $data)
    {
        return $this->chat
            ->create($data);
    }

    public function update(array $data,$id){
        return $this->chat
        ->where("id", $id)
        ->update($data);
    }

    public function delete($id){
        return $this->chat
        ->where("id", $id)
        ->delete();
    }

    public function deleteChat($id){
        return $this->chat
        ->destroy( $id);
    }
}
